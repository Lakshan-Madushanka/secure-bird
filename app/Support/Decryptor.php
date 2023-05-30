<?php

declare(strict_types=1);

namespace App\Support;

use App\Data\DecryptedMessageData;
use App\Events\DataChunkDecrypted;
use App\Events\DecryptionFail;
use App\Events\DecryptionSucceeded;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use SplFileObject;

class Decryptor
{
    private string $textPath;
    private string $mediaPath;
    private string $decryptedMediaPath;

    private int $textDecryptionPercentage = 0;
    private int $mediaDecryptionPercentage = 0;
    private bool $isSuccess = false;
    private int $totalPercentage = 0;

    /** @var array<string, string|null> */
    private array $metaData = [];

    /**
     * @param  string  $id
     * Relative path to storage
     * @param  string  $path
     */
    final private function __construct(
        private readonly string $id,
        private readonly string $path,
    ) {
        $this->mediaPath = "{$this->path}media/";
        $this->textPath = "{$this->path}text";
        $this->decryptedMediaPath = "{$this->mediaPath}original/";
    }

    public static function create(string $id, string $path): static
    {
        return new static($id, $path);
    }

    /**
     * @param  string  $textPath
     */
    public function setTextPath(string $textPath): void
    {
        $this->textPath = $textPath;
    }

    /**
     * Relative storage path to media directory
     * @param  string  $path
     */
    public function setMediaPath(string $path): void
    {
        $this->mediaPath = $path;
    }

    /**
     * @param  string  $decryptedMediaPath
     */
    public function setDecryptedMediaPath(string $decryptedMediaPath): void
    {
        $this->decryptedMediaPath = $decryptedMediaPath;
    }

    /** @param array<string, string|null> $metaData */
    public function setMetaData(array $metaData): void
    {
        $this->metaData = $metaData;
    }


    /**
     * Get progress in percentage
     * @return int
     */
    public function getProgress(): int
    {
        if ($this->totalPercentage === 0) {
            if ($this->textExists() && $this->mediaExists()) {
                $this->totalPercentage = 200;
            } else {
                $this->totalPercentage = 100;
            }
        }

        $progress = (int) round((($this->textDecryptionPercentage + $this->mediaDecryptionPercentage) / $this->totalPercentage) * 100);

        if ($this->isSuccess) {
            $progress = 100;
        }

        return $progress;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function decrypt(): void
    {
        try {
            $text = $this->decryptText();
            $this->decryptMedia();
        } catch (Exception $e) {
            Event::dispatch(new DecryptionFail($this->id));

            throw $e;
        }

        $this->isSuccess = true;

        $data = new DecryptedMessageData($text, $this->decryptedMediaPath);
        Event::dispatch(new DecryptionSucceeded($this->id, $data, $this->metaData));
    }

    private function decryptText(): string
    {
        if ( ! $this->textExists()) {
            return '';
        }

        $noOfLines = $this->getNoOfLines($this->textPath);
        $currentLine = 0;

        $file = new SplFileObject(Storage::path($this->textPath));

        $text = '';

        while ( ! $file->eof()) {
            $line = (string) $file->fgets();
            $newLine = str_replace(PHP_EOL, '', $line);

            $text .= Crypt::decrypt($newLine);

            $currentLine++;

            $this->setTextDecryptionPercentage($currentLine, $noOfLines);

            Event::dispatch(new DataChunkDecrypted($this->id, $this->getProgress()));

        }

        return $text;
    }

    private function decryptMedia(): void
    {
        if ( ! $this->mediaExists()) {
            return;
        }

        if ($this->decryptedMediaExists()) {
            return;
        }

        $media = Storage::files($this->mediaPath);

        $totalLines = $this->getNoOfLines($this->mediaPath);
        $currentLine = 0;

        $this->createDecryptedMediaDirectory($this->decryptedMediaPath);

        foreach ($media as $path) {
            $fileName = basename(Storage::path($path));

            $inputFile = fopen(Storage::path($path), 'rb');
            $decryptedFile = fopen(Storage::path($this->decryptedMediaPath.$fileName), 'wb');

            if ( ! $inputFile || ! $decryptedFile) {
                return;
            }

            $file = new SplFileObject(Storage::path($path));

            while ( ! $file->eof()) {
                $line = (string) $file->fgets();
                $newLine = str_replace(PHP_EOL, '', $line);

                try {
                    $decyptedLine = Crypt::decrypt($newLine);
                } catch (DecryptException $exception) {
                    $decyptedLine = $newLine;
                }

                if (is_string($decyptedLine)) {
                    fwrite($decryptedFile, $decyptedLine);
                }

                $currentLine++;

                $this->setMediaDecryptionPercentage($currentLine, $totalLines);

                Event::dispatch(new DataChunkDecrypted($this->id, $this->getProgress()));
            }

            $file = null;
        }
    }

    public function textExists(): bool
    {
        return Storage::exists($this->textPath);
    }

    public function mediaExists(): bool
    {
        return Storage::exists($this->mediaPath);
    }

    public function decryptedMediaExists(): bool
    {
        return Storage::exists($this->decryptedMediaPath);
    }

    public function getNoOfLines(string $path): int
    {
        if (Storage::fileExists($path)) {
            $file = new SplFileObject(Storage::path($path), 'r');
            $file->seek(PHP_INT_MAX);

            return $file->key() + 1;
        }

        $noOfLines = 1;
        $directories = Storage::files($path);

        foreach ($directories as $mediaPath) {
            $file = new SplFileObject(Storage::path($mediaPath), 'r');

            $file->seek(PHP_INT_MAX);
            $noOfLines += $file->key();
        }

        return $noOfLines;
    }

    public function createDecryptedMediaDirectory(string $directory): void
    {
        Storage::makeDirectory($directory);
    }

    private function setTextDecryptionPercentage(int $currentLine, int $totalLines): void
    {
        $percent = (int) round(($currentLine / $totalLines) * 100);

        $this->textDecryptionPercentage = $percent;

        if ($this->textDecryptionPercentage > 100) {
            $this->textDecryptionPercentage = 100;
        }
    }


    private function setMediaDecryptionPercentage(int $currentLine, int $totalLines): void
    {
        if ($totalLines === 0) {
            $this->mediaDecryptionPercentage = 100;
            return;
        }

        $percent = (int) round(($currentLine / $totalLines) * 100);

        $this->mediaDecryptionPercentage = $percent;

        if ($this->mediaDecryptionPercentage > 100) {
            $this->mediaDecryptionPercentage = 100;
        }
    }
}

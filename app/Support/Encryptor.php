<?php

declare(strict_types=1);

namespace App\Support;

use App\Events\DataChunkEncrypted;
use App\Events\EncryptionFail;
use App\Events\EncryptionSucceeded;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Encryptor
{
    private string $text;
    private string $textPath;
    private string $mediaPath;
    private int $textEncryptionPercentage = 0;
    private int $mediaEncryptionPercentage = 0;
    private bool $isSuccess = false;

    /**
     * @param  string  $id
     * Relative path to storage
     * @param  string  $path
     */
    final private function __construct(
        private readonly string $id,
        private readonly string $path,
    ) {
        $this->mediaPath = "{$this->path}media";
        $this->textPath = "{$this->path}text";
    }

    public static function create(string $id, string $path): static
    {
        return new static($id, $path);
    }

    /**
     * @param  string  $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @param  string  $textPath
     */
    public function setTextPath(string $textPath): void
    {
        $this->text = $textPath;
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
     * Get progress in percentage
     * @return int
     */
    public function getProgress(): int
    {
        $progress = (int) round((($this->textEncryptionPercentage + $this->mediaEncryptionPercentage) / 200) * 100);

        if ($this->isSuccess) {
            $progress = 100;
        }

        return $progress;
    }

    /**
     * @throws Exception
     */
    public function encrypt(): void
    {
        $this->setInitialProgress();

        try {
            $this->encryptText();
            $this->encryptMedia();
        } catch (Exception $e) {
            Event::dispatch(new EncryptionFail($this->id));
            Storage::delete($this->path);

            throw $e;
        }

        $this->isSuccess = true;
        Event::dispatch(new EncryptionSucceeded($this->id, $this->textPath));
    }

    public function setInitialProgress(): void
    {
        if ( ! isset($this->text)) {
            $this->textEncryptionPercentage = 100;
        }

        if ( ! Storage::directoryExists($this->mediaPath)) {
            $this->mediaEncryptionPercentage = 100;
        }
    }

    private function encryptText(): void
    {
        if ( ! isset($this->text)) {
            return;
        }

        $text = $this->text;
        $textLength = strlen($text);
        $chunkSize = 100;

        for ($i = 0; $i < $textLength; $i += $chunkSize) {
            $textChunk = substr($text, $i, $chunkSize);
            $encryptedTextChunk = Crypt::encrypt($textChunk);

            Storage::append($this->textPath, $encryptedTextChunk);

            $this->setTextEncryptionPercentage($i + $chunkSize, $textLength);

            Event::dispatch(new DataChunkEncrypted($this->id, $this->getProgress()));
        }
    }

    private function encryptMedia(): void
    {

        if ( ! Storage::exists($this->mediaPath)) {
            $this->setMediaEncryptionPercentage(0, 0);
            return;
        }

        $media = Storage::files($this->mediaPath);
        $size = $this->getMediaSize($media);

        $chunkSize = 2 * (10 ** 6); // 2 megabytes
        $currentChunkSize = 0;

        foreach ($media as $path) {
            $fileName = basename(Storage::path($path));
            $encryptedFileName = Str::random();

            $inputFile = fopen(Storage::path($path), 'rb');
            $encryptedFile = fopen(Storage::path($this->mediaPath.$encryptedFileName), 'wb');

            if ( ! $inputFile || ! $encryptedFile) {
                return;
            }

            while ( ! feof($inputFile)) {
                $chunk = fread($inputFile, $chunkSize);
                $encryptedChunk = Crypt::encrypt($chunk);

                $writtenBytes = fwrite($encryptedFile, $encryptedChunk.PHP_EOL);

                if ($writtenBytes) {
                    $currentChunkSize += $writtenBytes;
                    $this->setMediaEncryptionPercentage($currentChunkSize, $size);
                }

                Event::dispatch(new DataChunkEncrypted($this->id, $this->getProgress()));
            }

            fclose($inputFile);
            fclose($encryptedFile);

            Storage::move($this->mediaPath.$encryptedFileName, $path);
        }
    }

    /**
     * @param  string[]  $media
     * @return int
     */
    public function getMediaSize(array $media): int
    {
        $size = 0;

        foreach ($media as $path) {
            $size += Storage::size($path);
        }

        return $size;
    }

    private function setTextEncryptionPercentage(int $currentSize, int $length): void
    {
        $percent = (int) round(($currentSize / $length) * 100);

        $this->textEncryptionPercentage = $percent;

        if ($this->textEncryptionPercentage > 100) {
            $this->textEncryptionPercentage = 100;
        }
    }


    private function setMediaEncryptionPercentage(int $currentSize, int $totalSize): void
    {
        if ($totalSize === 0) {
            $this->mediaEncryptionPercentage = 100;
            return;
        }

        $percent = (int) round(($currentSize / $totalSize) * 100);

        $this->mediaEncryptionPercentage = $percent;

        if ($this->mediaEncryptionPercentage > 100) {
            $this->mediaEncryptionPercentage = 100;
        }
    }
}

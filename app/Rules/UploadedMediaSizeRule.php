<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class UploadedMediaSizeRule implements ValidationRule
{
    /**
     * Maximum allowed size in kilobytes
     * @param  int $size
     */
    public function __construct(private int $size)
    {
    }

    /**
     * Run the validation rule.
     * @param  Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ( ! is_array($value)) {
            return;
        }

        if (count($value) === 0) {
            return;
        }

        $size = 0;

        collect($value)->each(function ($file) use (&$size): void {
            if ($file instanceof UploadedFile) {
                $size += $file->getSize();
            }
        });


        if (($size/1024) > $this->size) {
            // @phpstan-ignore-next-line
            $fail('validation.uploadedMediaSize')?->translate([
                'size' => $this->size / 1000
            ]);
        }
    }
}

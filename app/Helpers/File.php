<?php

declare(strict_types=1);

namespace App\Helpers;

class File
{
    public static function sanitizeName(string $fileName): string
    {
        return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
    }
}

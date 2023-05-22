<?php

declare(strict_types=1);

namespace App\Enums;

enum EncryptionStatus: string
{
    case Started = 'started';
    case Success = 'success';
    case Fail = 'fail';
}

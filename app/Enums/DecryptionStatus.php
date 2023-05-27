<?php

declare(strict_types=1);

namespace App\Enums;

enum DecryptionStatus: string
{
    case Started = 'started';
    case Success = 'success';
    case Fail = 'fail';
}

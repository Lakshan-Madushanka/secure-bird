<?php

declare(strict_types=1);

namespace App\Actions;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CheckPasswordAction
{
    /**
     * @param  string  $password
     * @param  string  $hashedPassword
     * @return bool
     * @throws ValidationException
     */
    public function execute(string $password, string $hashedPassword): bool
    {
        if ( ! Hash::check($password, $hashedPassword)) {
            throw ValidationException::withMessages([
                'password' => __('validation.invalid_password')
            ]);
        }

        return true;
    }
}

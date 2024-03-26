<?php

namespace App\Rules;

use App\Models\Message;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MessageRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = Message::query()
            ->whereId($value)
            ->valid()
            ->exists();

        if (!$exists) {
            $fail('Invalid message Id');
        }
    }
}

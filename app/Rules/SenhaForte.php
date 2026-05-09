<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SenhaForte implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (strlen($value) < 8) {
            $fail('A senha deve ter no mínimo 8 caracteres.');
            return;
        }

        if (!preg_match('/[A-Z]/', $value)) {
            $fail('A senha deve conter pelo menos uma letra maiúscula.');
            return;
        }

        if (!preg_match('/[a-z]/', $value)) {
            $fail('A senha deve conter pelo menos uma letra minúscula.');
            return;
        }

        if (!preg_match('/[0-9]/', $value)) {
            $fail('A senha deve conter pelo menos um número.');
            return;
        }

        if (!preg_match('/[\W_]/', $value)) {
            $fail('A senha deve conter pelo menos um caractere especial (ex: !@#$%&*).');
            return;
        }
    }
}
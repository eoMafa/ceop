<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CpfValido implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cpf = preg_replace('/\D/', '', $value);

        if (strlen($cpf) !== 11) {
            $fail('O CPF informado é inválido.');
            return;
        }

        // Rejeita CPFs com todos os dígitos iguais
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            $fail('O CPF informado é inválido.');
            return;
        }

        // Valida dígitos verificadores
        for ($t = 9; $t < 11; $t++) {
            $sum = 0;
            for ($i = 0; $i < $t; $i++) {
                $sum += $cpf[$i] * (($t + 1) - $i);
            }
            $remainder = (10 * $sum) % 11;
            $digit = $remainder < 2 ? 0 : 11 - $remainder;

            if ($cpf[$t] != $digit) {
                $fail('O CPF informado é inválido.');
                return;
            }
        }
    }
}
<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CpfCnpjValido implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $documento = preg_replace('/\D/', '', $value);

        if (strlen($documento) === 11) {

            if (!$this->validarCpf($documento)) {
                $fail('O CPF informado é inválido.');
            }

            return;
        }

        if (strlen($documento) === 14) {

            if (!$this->validarCnpj($documento)) {
                $fail('O CNPJ informado é inválido.');
            }

            return;
        }

        $fail('Documento inválido.');
    }

    private function validarCpf(string $cpf): bool
    {
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {

            $sum = 0;

            for ($i = 0; $i < $t; $i++) {
                $sum += $cpf[$i] * (($t + 1) - $i);
            }

            $digit = ((10 * $sum) % 11) % 10;

            if ($cpf[$t] != $digit) {
                return false;
            }
        }

        return true;
    }

    private function validarCnpj(string $cnpj): bool
    {
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        $weights1 = [5,4,3,2,9,8,7,6,5,4,3,2];
        $weights2 = [6,5,4,3,2,9,8,7,6,5,4,3,2];

        $sum = 0;

        for ($i = 0; $i < 12; $i++) {
            $sum += $cnpj[$i] * $weights1[$i];
        }

        $rest = $sum % 11;
        $digit1 = $rest < 2 ? 0 : 11 - $rest;

        if ($cnpj[12] != $digit1) {
            return false;
        }

        $sum = 0;

        for ($i = 0; $i < 13; $i++) {
            $sum += $cnpj[$i] * $weights2[$i];
        }

        $rest = $sum % 11;
        $digit2 = $rest < 2 ? 0 : 11 - $rest;

        return $cnpj[13] == $digit2;
    }
}
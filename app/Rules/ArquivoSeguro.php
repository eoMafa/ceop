<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class ArquivoSeguro implements ValidationRule
{
    // Tipos MIME permitidos e suas assinaturas em bytes (magic bytes)
    protected array $tiposPermitidos = [
        'image/jpeg'      => ["\xFF\xD8\xFF"],
        'image/png'       => ["\x89PNG"],
        'image/gif'       => ['GIF87a', 'GIF89a'],
        'image/webp'      => ['RIFF'],
        'video/mp4'       => ["\x00\x00\x00", 'ftyp'],
        'video/quicktime' => ['ftyp', 'moov'],
        'video/x-msvideo' => ['RIFF'],
        'application/pdf' => ['%PDF'],
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$value instanceof UploadedFile) {
            $fail('O arquivo enviado é inválido.');
            return;
        }

        // Verifica se o MIME type é permitido
        $mime = $value->getMimeType();

        if (!array_key_exists($mime, $this->tiposPermitidos)) {
            $fail('Tipo de arquivo não permitido. Aceito: imagens, vídeos e PDF.');
            return;
        }

        // Verifica o tamanho máximo (100MB)
        if ($value->getSize() > 100 * 1024 * 1024) {
            $fail('O arquivo não pode ser maior que 100MB.');
            return;
        }

        // Verifica os magic bytes do arquivo
        $handle = fopen($value->getRealPath(), 'rb');
        $bytes  = fread($handle, 12);
        fclose($handle);

        $assinaturaValida = false;
        foreach ($this->tiposPermitidos[$mime] as $assinatura) {
            if (str_starts_with($bytes, $assinatura) || str_contains($bytes, $assinatura)) {
                $assinaturaValida = true;
                break;
            }
        }

        if (!$assinaturaValida) {
            $fail('O conteúdo do arquivo não corresponde ao tipo informado.');
            return;
        }

        // Verificação extra para imagens — tenta carregar como imagem
        if (str_starts_with($mime, 'image/')) {
            $info = @getimagesize($value->getRealPath());
            if ($info === false) {
                $fail('O arquivo de imagem está corrompido ou é inválido.');
                return;
            }
        }
    }
}
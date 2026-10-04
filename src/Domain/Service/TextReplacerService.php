<?php

namespace Oigonzalezp2024\ScaffoldEngine\Domain\Service;

class TextReplacerService
{
    /**
     * Aplica un mapa de sustituciones [buscar => reemplazar] a un texto.
     */
    public function replace(string $content, array $replacements): string
    {
        if (empty($replacements)) {
            return $content;
        }

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $content
        );
    }
}

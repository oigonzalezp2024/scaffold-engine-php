<?php

namespace Oigonzalezp2024\ScaffoldEngine\Domain\Exception;

use Exception;

class FileNotFoundException extends Exception
{
    public function __construct(string $path, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct(sprintf("El archivo no se encuentra en la ruta: '%s'", $path), $code, $previous);
    }
}

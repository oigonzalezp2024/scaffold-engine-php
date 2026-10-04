<?php

namespace Oigonzalezp2024\ScaffoldEngine\Presentation\DTO;

use InvalidArgumentException;

readonly class InstruccionesDTO
{
    /**
     * @param string $basePath Ruta base absoluta del sistema de archivos
     * @param array<int, array{0: string, 1: string, 2: array<string, string>}> $receta
     */
    public function __construct(
        public string $basePath,
        public array $receta
    ) {}

    /**
     * Construye y valida el DTO a partir de un array asociativo proveniente de JSON.
     *
     * @throws InvalidArgumentException Si la estructura no es válida.
     */
    public static function fromArray(array $payload, string $defaultBasePath): self
    {
        $basePath = $payload['basePath'] ?? $defaultBasePath;
        $receta = $payload['receta'] ?? null;

        if (!is_array($receta)) {
            throw new InvalidArgumentException("El campo 'receta' es obligatorio y debe ser un arreglo de instrucciones.");
        }

        foreach ($receta as $index => $item) {
            if (!is_array($item) || count($item) < 3) {
                throw new InvalidArgumentException("La instrucción en el índice {$index} debe contener [origen, destino, reemplazos].");
            }
            if (!is_string($item[0]) || !is_string($item[1]) || !is_array($item[2])) {
                throw new InvalidArgumentException("Tipos inválidos en la instrucción del índice {$index}.");
            }
        }

        return new self($basePath, $receta);
    }
}

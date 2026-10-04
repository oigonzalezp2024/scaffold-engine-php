<?php

namespace Oigonzalezp2024\ScaffoldEngine\Infrastructure\Adapter;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class DirectoryMapGenerator
{
    /**
     * Genera un mapa de pares [origen, destino] recorriendo de forma recursiva.
     *
     * @param string $origen Ruta de la carpeta origen
     * @param string $destino Ruta de la carpeta destino
     * @return array<array{0: string, 1: string}>
     */
    public function generate(string $origen, string $destino): array
    {
        $mapa = [];

        if (!is_dir($origen)) {
            return $mapa;
        }

        // Normalizamos las rutas base eliminando barras finales sobrantes
        $origen = rtrim(str_replace('\\', '/', $origen), '/');
        $destino = rtrim(str_replace('\\', '/', $destino), '/');
        $longitudOrigen = strlen($origen);

        $directorio = new RecursiveDirectoryIterator($origen, RecursiveDirectoryIterator::SKIP_DOTS);
        $iterador = new RecursiveIteratorIterator($directorio);

        foreach ($iterador as $archivo) {
            if ($archivo->isFile()) {
                $rutaOrigen = str_replace('\\', '/', $archivo->getPathname());

                // Extraemos la ruta relativa cortando la parte base del origen
                $rutaRelativa = substr($rutaOrigen, $longitudOrigen);

                // Construimos la ruta de destino equivalente
                $rutaDestino = $destino . $rutaRelativa;

                $mapa[] = [$rutaOrigen, $rutaDestino];
            }
        }

        return $mapa;
    }
}

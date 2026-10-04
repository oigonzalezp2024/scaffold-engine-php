<?php

namespace Oigonzalezp2024\ScaffoldEngine\Bin;

require __DIR__ . '/../vendor/autoload.php';

use Oigonzalezp2024\ScaffoldEngine\Presentation\DTO\InstruccionesDTO;
use Oigonzalezp2024\ScaffoldEngine\Presentation\Console\AddModuleInventoryCommand;
use InvalidArgumentException;
use Throwable;

try {
    // 1. Obtener la ruta del JSON desde los argumentos
    $jsonPath = $argv[1] ?? __DIR__ . '/../receta.json';

    if (!file_exists($jsonPath)) {
        throw new InvalidArgumentException("No se encontró el archivo de receta JSON en: {$jsonPath}");
    }

    $rawJson = file_get_contents($jsonPath);
    $data = json_decode($rawJson, true, 512, JSON_THROW_ON_ERROR);

    // 2. Preparar DTO
    $basePath = realpath(__DIR__ . '/../') . '/';
    $dto = InstruccionesDTO::fromArray($data, $basePath);

    // 3. Ejecutar Comando
    $command = new AddModuleInventoryCommand();
    $result = $command->execute($dto);

    if ($result) {
        fwrite(STDOUT, "✔ Módulo instalado e integrado correctamente.\n");
        exit(0);
    } else {
        fwrite(STDERR, "✖ Error: El caso de uso devolvió 'false' durante el procesamiento.\n");
        exit(1);
    }

} catch (InvalidArgumentException $e) {
    // Captura errores de archivo no encontrado o JSON mal formado
    fwrite(STDERR, "⚠ Error de Validación: " . $e->getMessage() . "\n");
    exit(1);

} catch (Throwable $e) {
    // Captura cualquier otro error no esperado (permisos de archivos, excepciones de dominio/infraestructura, etc.)
    fwrite(STDERR, "✖ Error de Ejecución: " . $e->getMessage() . "\n");
    exit(1);
}

<?php

namespace Oigonzalezp2024\ScaffoldEngine\Presentation\Controller;

use Oigonzalezp2024\ScaffoldEngine\Application\FileCopyAndReplaceFromArrayCaseUse;
use Oigonzalezp2024\ScaffoldEngine\Domain\Service\TextReplacerService;
use Oigonzalezp2024\ScaffoldEngine\Infrastructure\Adapter\LocalFileManager;
use Oigonzalezp2024\ScaffoldEngine\Presentation\DTO\InstruccionesDTO;
use InvalidArgumentException;
use Throwable;

class AddModuleInventoryController
{
    /**
     * Procesa la solicitud HTTP API.
     *
     * @param string $jsonInput Contenido raw del cuerpo de la petición (JSON)
     * @param string $defaultBasePath Ruta base por defecto del servidor
     */
    public function handle(string $jsonInput, string $defaultBasePath): void
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            // 1. Decodificar JSON
            $data = json_decode($jsonInput, true, 512, JSON_THROW_ON_ERROR);

            // 2. Transformar y Validar DTO
            $dto = InstruccionesDTO::fromArray($data ?? [], $defaultBasePath);

            // 3. Invocación de Capa de Aplicación e Infraestructura
            $fileManager = new LocalFileManager($dto->basePath);
            $textReplacer = new TextReplacerService();

            $useCase = new FileCopyAndReplaceFromArrayCaseUse(
                $fileManager,
                $textReplacer,
                $dto->receta
            );

            $success = $useCase->run();

            // 4. Respuesta de Éxito
            if ($success) {
                http_response_code(200);
                echo json_encode([
                    'status'  => 'success',
                    'code'    => 200,
                    'message' => 'Módulo de inventario generado e instalado con éxito.'
                ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            } else {
                http_response_code(500);
                echo json_encode([
                    'status'  => 'error',
                    'code'    => 500,
                    'message' => 'No se pudo completar el procesamiento de archivos.'
                ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            }

        } catch (InvalidArgumentException $e) {
            // Error de validación de entrada
            http_response_code(400);
            echo json_encode([
                'status'  => 'fail',
                'code'    => 400,
                'message' => $e->getMessage()
            ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

        } catch (Throwable $e) {
            // Error inesperado / Falso JSON
            http_response_code(500);
            echo json_encode([
                'status'  => 'error',
                'code'    => 500,
                'message' => 'Error interno procesando la solicitud: ' . $e->getMessage()
            ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        }
    }
}

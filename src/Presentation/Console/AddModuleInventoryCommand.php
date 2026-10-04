<?php

namespace Oigonzalezp2024\ScaffoldEngine\Presentation\Console;

use Oigonzalezp2024\ScaffoldEngine\Application\FileCopyAndReplaceFromArrayCaseUse;
use Oigonzalezp2024\ScaffoldEngine\Domain\Service\TextReplacerService;
use Oigonzalezp2024\ScaffoldEngine\Infrastructure\Adapter\LocalFileManager;
use Oigonzalezp2024\ScaffoldEngine\Presentation\DTO\InstruccionesDTO;

class AddModuleInventoryCommand
{
    /**
     * Ejecuta la lógica del módulo de inventario basándose en el DTO recibido.
     */
    public function execute(InstruccionesDTO $dto): bool
    {
        // Se construyen las dependencias del caso de uso
        $fileManager = new LocalFileManager($dto->basePath);
        $textReplacer = new TextReplacerService();

        $useCase = new FileCopyAndReplaceFromArrayCaseUse(
            $fileManager,
            $textReplacer,
            $dto->receta
        );

        return $useCase->run();
    }
}

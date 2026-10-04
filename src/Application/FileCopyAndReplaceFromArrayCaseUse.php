<?php

namespace Oigonzalezp2024\ScaffoldEngine\Application;

use Oigonzalezp2024\ScaffoldEngine\Domain\FileManagerInterface;
use Oigonzalezp2024\ScaffoldEngine\Domain\Service\TextReplacerService;
use Exception;

class FileCopyAndReplaceFromArrayCaseUse
{
    private FileManagerInterface $fileManager;
    private TextReplacerService $textReplacer;
    private array $data;

    /**
     * @param FileManagerInterface $fileManager Operaciones I/O sobre el sistema de archivos.
     * @param TextReplacerService $textReplacer Servicio de transformación de texto.
     * @param array $data Matriz estructurada: [
     *     ['origen', 'destino', ['buscar1' => 'reemplazo1', ...]],
     *     ['origen2', 'destino2', ['buscar2' => 'reemplazo2']]
     * ]
     */
    public function __construct(
        FileManagerInterface $fileManager,
        TextReplacerService $textReplacer,
        array $data
    ) {
        $this->fileManager = $fileManager;
        $this->textReplacer = $textReplacer;
        $this->data = $data;
    }

    /**
     * Ejecuta el proceso de lectura, reemplazo y copia para cada elemento.
     *
     * @return bool
     * @throws Exception Si falla la validación o escritura de algún archivo.
     */
    public function run(): bool
    {
        foreach ($this->data as $element) {
            $source = $element[0];
            $destination = $element[1];
            $replacements = $element[2] ?? [];

            $this->processFile($source, $destination, $replacements);
        }

        return true;
    }

    /**
     * Coordina la validación, lectura, transformación y escritura por cada archivo.
     */
    private function processFile(string $source, string $destination, array $replacements): void
    {
        $this->validateSourceFile($source);

        $content = $this->fileManager->fileRead($source);
        $modifiedContent = $this->textReplacer->replace($content, $replacements);

        $success = $this->fileManager->fileWrite($destination, $modifiedContent);

        if (!$success) {
            throw new Exception("Falla al escribir el archivo de destino en: {$destination}");
        }
    }

    /**
     * Valida que la fuente exista y corresponda a un archivo.
     */
    private function validateSourceFile(string $source): void
    {
        if (!$this->fileManager->fileExists($source) || !$this->fileManager->isFile($source)) {
            throw new Exception("El archivo de origen no existe o no es válido: {$source}");
        }
    }
}

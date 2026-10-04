<?php

namespace Oigonzalezp2024\ScaffoldEngine\Infrastructure\Adapter;

use Oigonzalezp2024\ScaffoldEngine\Domain\FileManagerInterface;
use Oigonzalezp2024\ScaffoldEngine\Domain\Exception\FileNotFoundException;

class LocalFileManager implements FileManagerInterface
{
    private string $basePath;

    /**
     * @param string $basePath Directorio base seguro donde se gestionarán los archivos.
     */
    public function __construct(string $basePath)
    {
        $resolvedBase = realpath($basePath);
        $this->basePath = $resolvedBase !== false ? $resolvedBase : rtrim($basePath, '/\\');
    }

    /**
     * Resuelve la ruta de forma segura previniendo la navegación fuera de basePath.
     */
    private function resolvePath(string $path): string
    {
        // Normalizamos prefijos relativos ./ o .\
        $cleanPath = preg_replace('#^(\.[\/\\\\])+#', '', $path);
        $fullPath = $this->basePath . DIRECTORY_SEPARATOR . ltrim($cleanPath, '/\\');

        // Si el archivo o directorio existe, verificamos que no haya escapado de basePath
        $realPath = realpath($fullPath);
        if ($realPath !== false && strpos($realPath, $this->basePath) !== 0) {
            throw new \InvalidArgumentException(sprintf("Acceso denegado fuera de la ruta base: '%s'", $path));
        }

        return $fullPath;
    }

    public function isFile(string $path): bool
    {
        $fullPath = $this->resolvePath($path);
        return is_file($fullPath);
    }

    public function fileExists(string $path): bool
    {
        $fullPath = $this->resolvePath($path);
        return file_exists($fullPath) && is_file($fullPath);
    }

    public function fileRead(string $path): string
    {
        if (!$this->fileExists($path)) {
            throw new FileNotFoundException($path);
        }

        $fullPath = $this->resolvePath($path);
        $content = file_get_contents($fullPath);

        if ($content === false) {
            throw new \RuntimeException(sprintf("No se pudo leer el archivo en la ruta: '%s'", $path));
        }

        return $content;
    }

    public function fileWrite(string $path, string $contents): bool
    {
        $fullPath = $this->resolvePath($path);

        $directory = dirname($fullPath);
        if (!is_dir($directory)) {
            if (!mkdir($directory, 0755, true) && !is_dir($directory)) {
                return false;
            }
        }

        return file_put_contents($fullPath, $contents) !== false;
    }

    public function fileDelete(string $path): bool
    {
        if (!$this->fileExists($path)) {
            return false;
        }

        return unlink($this->resolvePath($path));
    }

    public function fileSize(string $path): int
    {
        if (!$this->fileExists($path)) {
            throw new FileNotFoundException($path);
        }

        $fullPath = $this->resolvePath($path);
        $size = filesize($fullPath);

        if ($size === false) {
            throw new \RuntimeException(sprintf("No se pudo obtener el tamaño del archivo: '%s'", $path));
        }

        return $size;
    }

    public function fileCopy(string $source, string $destination): bool
    {
        if (!$this->fileExists($source)) {
            throw new FileNotFoundException($source);
        }

        $fullSource = $this->resolvePath($source);
        $fullDestination = $this->resolvePath($destination);

        $directory = dirname($fullDestination);
        if (!is_dir($directory)) {
            if (!mkdir($directory, 0755, true) && !is_dir($directory)) {
                return false;
            }
        }

        return copy($fullSource, $fullDestination);
    }

    public function fileMove(string $source, string $destination): bool
    {
        if (!$this->fileExists($source)) {
            throw new FileNotFoundException($source);
        }

        $fullSource = $this->resolvePath($source);
        $fullDestination = $this->resolvePath($destination);

        $directory = dirname($fullDestination);
        if (!is_dir($directory)) {
            if (!mkdir($directory, 0755, true) && !is_dir($directory)) {
                return false;
            }
        }

        return rename($fullSource, $fullDestination);
    }
}

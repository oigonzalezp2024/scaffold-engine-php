<?php

namespace Oigonzalezp2024\ScaffoldEngine\Domain;

use Oigonzalezp2024\ScaffoldEngine\Domain\Exception\FileNotFoundException;

interface FileManagerInterface {
    public function fileRead(string $path): string;
    public function fileWrite(string $path, string $contents): bool;
    public function fileExists(string $path): bool;
    public function fileDelete(string $path): bool;
    public function fileSize(string $path): int;
    public function fileCopy(string $source, string $destination): bool;
    public function fileMove(string $source, string $destination): bool;
    public function isFile(string $path): bool;
}

/**
 * Interfaz para la gestión integral de archivos en la capa de dominio.
 */
interface FileManagerInterfaceDoc {

    /**
     * Lee el contenido de un archivo en la ruta especificada.
     *
     * @param string $path Ruta o identificador del archivo.
     * @return string Contenido del archivo.
     * @throws \Oigonzalezp2024\ScaffoldEngine\Domain\Exception\FileNotFoundException Si el archivo no existe.
     */
    public function fileRead(string $path): string;

    /**
     * Escribe contenido en un archivo en la ruta especificada.
     *
     * @param string $path Ruta o identificador del archivo.
     * @param string $contents Contenido que se va a escribir.
     * @return bool True si la operación fue exitosa, false en caso contrario.
     */
    public function fileWrite(string $path, string $contents): bool;

    /**
     * Verifica si un archivo o directorio existe en la ruta dada.
     *
     * @param string $path Ruta a verificar.
     * @return bool True si existe, false en caso contrario.
     */
    public function fileExists(string $path): bool;

    /**
     * Elimina un archivo en la ruta especificada.
     *
     * @param string $path Ruta del archivo a eliminar.
     * @return bool True si se eliminó con éxito, false en caso contrario.
     */
    public function fileDelete(string $path): bool;

    /**
     * Obtiene el tamaño del archivo en bytes.
     *
     * @param string $path Ruta del archivo.
     * @return int Tamaño del archivo en bytes.
     */
    public function fileSize(string $path): int;

    /**
     * Copia un archivo de una ruta de origen a una de destino.
     *
     * @param string $source Ruta de origen.
     * @param string $destination Ruta de destino.
     * @return bool True si se copió con éxito.
     */
    public function fileCopy(string $source, string $destination): bool;

    /**
     * Mueve o renombra un archivo.
     *
     * @param string $source Ruta actual del archivo.
     * @param string $destination Nueva ruta o nombre.
     * @return bool True si se movió con éxito.
     */
    public function fileMove(string $source, string $destination): bool;
}

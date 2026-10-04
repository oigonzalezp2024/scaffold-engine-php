<?php

require __DIR__ . '/../../vendor/autoload.php';

use Oigonzalezp2024\ScaffoldEngine\Presentation\Controller\AddModuleInventoryController;

// Obtener el cuerpo de la petición HTTP (POST RAW JSON)
$jsonInput = file_get_contents('php://input');
$defaultBasePath = realpath(__DIR__ . '/../../') . '/';

$controller = new AddModuleInventoryController();
$controller->handle($jsonInput, $defaultBasePath);

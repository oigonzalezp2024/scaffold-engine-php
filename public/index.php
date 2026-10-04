<?php

require __DIR__ . '/../vendor/autoload.php';

use Oigonzalezp2024\ScaffoldEngine\Presentation\DTO\InstruccionesDTO;
use Oigonzalezp2024\ScaffoldEngine\Presentation\Console\AddModuleInventoryCommand;

// 1. Preparación de datos de entrada / Receta
$data = [
    [
        './template/.env',
        './public/proyectos/inventario/.env',
        [
            'APP_ENV=local' => 'APP_ENV=production',
            'DB_NAME=base'  => 'DB_NAME=inventario'
        ]
    ],
    [
        './template/composer.json',
        './public/proyectos/inventario/composer.json',
        [
            'your-vendor/your-project' => 'oigonzalezp2024/your-project'
        ]
    ],
    [
        './template/composer.json',
        './public/proyectos/inventario/composer2.json',
        []
    ]
];

$basePath = __DIR__ . '/../';

// 2. Creación del DTO
$instruccionesDTO = new InstruccionesDTO($basePath, $data);

// 3. Invocación de la Capa de Presentación
$command = new AddModuleInventoryCommand();
$result = $command->execute($instruccionesDTO);

if ($result) {
    echo "¡Módulo de inventario generado e instalado con éxito!\n";
} else {
    echo "Ocurrió un error al procesar las instrucciones.\n";
}

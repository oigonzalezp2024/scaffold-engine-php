<?php

namespace Oigonzalezp2024\ScaffoldEngine\Domain;

class ModuloInventario {

    private string $lib_mat;

    public function __construct(string $lib_mat) {
        $this->lib_mat = $lib_mat;
    }

    public function getLibMat(): string {
        return $this->lib_mat;
    }
}

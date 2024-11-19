<?php

class CategoriaModel{

    private $database;
    public function __construct($database){
        $this->database = $database;
    }

    public function obtenerTodasCategorias(){
        return $this->database->obtenerTodasCategorias();
    }

    public function dameTodasLasCategoriasMenosLaQueTePaso($idCategoriaAExcluir){
        $categoriasTotales = $this->obtenerTodasCategorias();

        $categoriasFiltradas = array_filter($categoriasTotales, function($categoria) use ($idCategoriaAExcluir) {
            return $categoria['id_categoria'] !== $idCategoriaAExcluir;
        });

        return array_values($categoriasFiltradas);
    }

}
<?php

class CategoriaModel{

    private $database;
    public function __construct($database){
        $this->database = $database;
    }

    public function obtenerTodasCategorias(){
        return $this->database->obtenerTodasCategorias();
    }

}
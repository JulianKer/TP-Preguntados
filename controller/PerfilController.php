<?php

class PerfilController{

    private $model;
    private $presenter;
    public function __construct($model,$presenter){
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function usuario(){
        $idDelUserDeLaSesion = isset($_SESSION['idUser']) ? $_SESSION['idUser'] : "";
        $userEncontrado = $this->model->obtenerUsuarioPorId($idDelUserDeLaSesion)[0];

        list($latitud, $longitud) = explode(", ", $userEncontrado["ubicacion"]);
        $userEncontrado["lng"] = $longitud;
        $userEncontrado["lat"] = $latitud;

        $data['usuario'] = $userEncontrado;
        $data['user'] = $_SESSION['user'];
        $data["estoyEnPerfilDelUsuario"] = true; // ver el tema de comparar ids del user segun sea si estoy en perfil del user de la sesion o si estoy en el perfil de algun user que toque del ranking

        $this->presenter->show("perfil", $data);
    }
















}
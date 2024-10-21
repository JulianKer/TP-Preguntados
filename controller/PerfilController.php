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

        //echo "el id es: " . $idDelUserDeLaSesion;
        $userEncontrado = $this->model->obtenerUsuarioPorId($idDelUserDeLaSesion)[0];

        $data['usuario'] = $userEncontrado;
        $data['user'] = $_SESSION['user'];
        $this->presenter->show("perfil", $data);
    }
















}
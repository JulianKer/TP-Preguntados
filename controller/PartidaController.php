<?php

class PartidaController
{
    private $presenter;
    private $model;
    public function __construct($model, $presenter)
    {
        $this->presenter = $presenter;
        $this->model = $model;
    }

public function iniciarPartida(){
    if (!isset($_SESSION['user'])) {
        header("location: /acceso/ingresar");
    }
    $data['user'] = $_SESSION['user'];
    $data['idUsuario'] = isset($_SESSION['idUsuario']);
    $this ->presenter ->show("partida", $data);
}


}
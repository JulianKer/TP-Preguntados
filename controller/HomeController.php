<?php

class HomeController
{
    private $model;
    private $modelPartida;
    private $presenter;

    public function __construct($model, $modelPartida, $presenter)
    {
        $this->model = $model;
        $this->modelPartida = $modelPartida;
        $this->presenter = $presenter;
    }

    public function inicio(){
        if (!isset($_SESSION['user'])) {
            header("location: /acceso/ingresar");
            exit();
        }
        $data['user'] = $_SESSION['user'];
        $data['idUsuario'] = $_SESSION['idUser'];
        $data["partidaPendiente"] = (bool)$this->modelPartida->buscarSiHayUnaPartidaEnCursoParaEsteUser($_SESSION['idUser']);

        $this->presenter->show('home', $data);
    }

    public function redirectHome()
    {
        header('location: /principal/inicio');
        exit();
    }
}
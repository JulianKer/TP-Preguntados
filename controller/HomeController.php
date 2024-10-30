<?php

class HomeController
{
    private $model;
    private $modelPartida;
    private $presenter;

    public function __construct($model, $modelPartida, $modelUsuario, $presenter)
    {
        $this->model = $model;
        $this->modelPartida = $modelPartida;
        $this->modelUsuario = $modelUsuario;
        $this->presenter = $presenter;
    }

    public function inicio(){
        if (!isset($_SESSION['user'])) {
            header("location: /acceso/ingresar");
            exit();
        }
        $userEncontrado = $this->modelUsuario->obtenerUsuarioPorId($_SESSION['idUser'])[0];

        $data["musicaActivada"] = $userEncontrado["musica"];
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
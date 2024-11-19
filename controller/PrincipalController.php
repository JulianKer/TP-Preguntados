<?php

class PrincipalController
{
    private $model;
    private $modelPartida;
    private $presenter;
    private $modelUsuario;
    private $modelRanking;
    public function __construct($model, $modelPartida, $modelUsuario, $modelRanking, $presenter)
    {
        $this->model = $model;
        $this->modelPartida = $modelPartida;
        $this->modelUsuario = $modelUsuario;
        $this->modelRanking = $modelRanking;
        $this->presenter = $presenter;
    }

    public function inicio(){
        /*if (!isset($_SESSION['user'])) {
            header("location: /acceso/ingresar");
            exit();
        }*/
        Seguridad::verSiHayUnUsuarioEnLaSesion();

        $_SESSION["errorCrear"] = null;
        $_SESSION["exitoCrear"] = null;
        $userEncontrado = $this->modelUsuario->obtenerUsuarioPorId($_SESSION['idUser'])[0];

        $data["musicaActivada"] = $userEncontrado["musica"];
        $data["objUsuario"] = $userEncontrado;
        $data['user'] = $_SESSION['user'];
        $data['idUsuario'] = $_SESSION['idUser'];
        $data["partidaPendiente"] = (bool)$this->modelPartida->buscarSiHayUnaPartidaEnCursoParaEsteUser($_SESSION['idUser']);
        $data["posicionEnElRanking"] = $this->modelRanking->dameLaPosicionEnElRankingDeEsteUsuario($userEncontrado["id"]);
        $data["partidasDelUsuario"] = $this->modelPartida->obtenerPartidasDelUsuario($userEncontrado["id"]);


        $data["esJugador"] = $this->modelUsuario->saberSiEsJugador($userEncontrado["rango"]);
        $data["esEditor"] = $this->modelUsuario->saberSiEsEditor($userEncontrado["rango"]);
        $data["esAdmin"] = $this->modelUsuario->saberSiEsAdmin($userEncontrado["rango"]);

        $this->presenter->show('home', $data);
    }

    public function redirectHome()
    {
        header('location: /principal/inicio');
        exit();
    }


    /*-----------------------------------------------------------------------------------*/
}
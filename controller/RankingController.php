<?php

class RankingController{

    private $model;
    private $modelUsuario;
    private $modelPartida;
    private $presenter;

    public function __construct($model,$modelUsuario,$modelPartida, $presenter){
        $this->model = $model;
        $this->modelUsuario = $modelUsuario;
        $this->modelPartida = $modelPartida;
        $this->presenter = $presenter;
    }

    public function inicio(){
        header('location: /ranking/posiciones');
        exit();
    }

    public function posiciones(){
        if (!isset($_SESSION['user'])) {
            header("location: /acceso/ingresar");
            exit();
        }
        $userEncontrado = $this->modelUsuario->obtenerUsuarioPorId($_SESSION['idUser'])[0];
        $data["musicaActivada"] = $userEncontrado["musica"];
        $data['user'] = $_SESSION['user'];
        $data["partidaPendiente"] = (bool)$this->modelPartida->buscarSiHayUnaPartidaEnCursoParaEsteUser($_SESSION['idUser']);

        $partidasDelRanking = $this->model->dameElRanking();
        $data["ranking"] = $partidasDelRanking;

        $this->presenter->show('ranking', $data);
    }
}
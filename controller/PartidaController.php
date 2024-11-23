<?php

class   PartidaController
{
    private $presenter;
    private $model;
    private $userModel;
    private $preguntaModel;
    public function __construct($model, $userModel, $preguntaModel, $presenter){
        $this->presenter = $presenter;
        $this->model = $model;
        $this->userModel = $userModel;
        $this->preguntaModel = $preguntaModel;
    }

    public function inicio(){
        header('location: /principal/inicio');
        exit();
    }

    public function jugar() {
        if (!isset($_SESSION['user'])) {
            header("location: /acceso/ingresar");
            exit;
        }

        $sessionData['user'] = $_SESSION['user'];
        $postData['respuesta'] = isset($_POST['respuesta']) ? $_POST['respuesta'] : null;
        $postData['id_pregunta'] = isset($_POST['id_pregunta']) ? $_POST['id_pregunta'] : null;

        $data = [];
        $data['user'] = $sessionData['user'];

        $idUser = $this->userModel->obtenerIdUserPorUserName($sessionData['user'])["id"];
        $userEncontrado = $this->userModel->obtenerUsuarioPorId($idUser)[0];

        $data['idUsuario'] = $idUser;
        $data['musicaActivada'] = $userEncontrado["musica"];
        $data['objUsuario'] = $userEncontrado;

        $data['partida'] = $this->model->obtenerPartidaEnCursoDelUserOCrearUnaNuevaPartida($idUser);

        if (!$postData['respuesta']) {
            $data = array_merge($data, $this->model->entregarPregunta($data, $sessionData));
            $_SESSION['respuestas_desordenadas'] = $data['opciones'];
        } else {
            $sessionData['respuestas_desordenadas'] = $_SESSION['respuestas_desordenadas'];
            $data = array_merge($data, $this->model->procesarRespuesta($data, $postData, $sessionData));
        }

        $this->presenter->show("partida", $data);
    }

    public function mostrarVistaReporte(){

        if (!isset($_GET['id_pregunta'])) {
            header("Location: /partida/jugar");
            exit;
        }
        $idPregunta = $_GET['id_pregunta'];


        if (!isset($_SESSION['user'])) {
            header("Location: /acceso/ingresar");
            exit;
        }
        $pregunta = $this->preguntaModel->obtenerPregunta($idPregunta);
        if (!$pregunta) {
            header("Location: /partida/jugar");
            exit;
        }
        $data['idPreguntaReportar'] = $idPregunta;
        $data['hola'] = "hola";
        $data['idUser'] = $_SESSION['idUser'];


        $data['user'] = $_SESSION['user'];
        $idUser = $this->userModel->obtenerIdUserPorUserName($_SESSION['user'])["id"];
        $userEncontrado = $this->userModel->obtenerUsuarioPorId($idUser)[0];
        $data['musicaActivada'] = $userEncontrado["musica"];
        $data['objUsuario'] = $userEncontrado;


        $this->presenter->show("reportarPregunta", $data);
    }

    public function reportarPregunta(){
        $id_pregunta = "";
        $descripcion = "";
        $id_usuario = "";

        if (!isset($_SESSION['user'])) {
            header("location: /acceso/ingresar");
            exit;
        }
        if(!isset($_POST['id_pregunta' ]) || !isset($_POST['descripcion' ])){
         header( "location : /principal/inicio");
        }
        $id_pregunta = $_POST['id_pregunta'];
        $descripcion = $_POST['descripcion'];
        $id_usuario = $this -> userModel -> obtenerIdUserPorUserName($_SESSION['user'])["id"];
        $estadoRevision = 2;

        $this->preguntaModel->actualizarEstadoPregunta($id_pregunta, $estadoRevision);
        $this ->preguntaModel->crearReporte($id_pregunta, $id_usuario, $descripcion);

        header("location: /principal/home");
        exit();
    }
}
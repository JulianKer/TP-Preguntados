<?php

class   PartidaController
{
    private $presenter;
    private $model;
    private $userModel;
    private $preguntaModel;
    public function __construct($model, $userModel, $preguntaModel, $presenter)
    {
        $this->presenter = $presenter;
        $this->model = $model;
        $this->userModel = $userModel;
        $this->preguntaModel = $preguntaModel;
    }

    public function inicio()
    {
        header('location: /partida/jugar');
        exit();
    }

    public function jugar() {
        if (!isset($_SESSION['user'])) {
            header("location: /acceso/ingresar");
            exit;
        }

        $data['user'] = $_SESSION['user'];
        $idUser = $this->userModel->obtenerIdUserPorUserName($_SESSION['user'])["id"];
        $userEncontrado = $this->userModel->obtenerUsuarioPorId($idUser)[0];
        $data['idUsuario'] = $idUser;
        $data['musicaActivada'] = $userEncontrado["musica"];
        $data['objUsuario'] = $userEncontrado;

        $partida = $this->model->buscarSiHayUnaPartidaEnCursoParaEsteUser($idUser);
        if ($partida === null) {
            $idPartidaABuscar = $this->model->crearPartidaEnCursoParaEsteUser($idUser);
            $partida = $this->model->buscarLaUltimaPartidaInsertada($idPartidaABuscar);
        }

        if (!isset($_POST["respuesta"])) {
            $preguntaPartida = $this->model->buscarUltimaPreguntaNoResponididaDeLaPartida($partida["id_partida"]);

            if ($preguntaPartida === null) {
                $idsPreguntasRespondidas = $this->model->buscarPreguntasResponidasPorElUsuario($idUser);
                $idsPreguntasTotales = $this->preguntaModel->obtenerIdsDeTodasLasPreguntasQueExisten();
                $idsPreguntasNoRespondidas = array_values(array_diff($idsPreguntasTotales, $idsPreguntasRespondidas)); // con el array values lo reindexo x si me quedaron huecos entre meidio del array
                $numPregunta = $this->model->seleccionarPreguntaAleatoriaQueElUserNoHayaRespondido($idsPreguntasNoRespondidas);

                if ($numPregunta === 0){
                    $this->model->resetearPreguntaPartidaDeLasPreguntasRespondidasPorEsteUsuario($idUser); //elimino todas las preguntas que respondio tal usuario y vuelvo a pedir un numPregunta random
                    $idsPreguntasRespondidas = $this->model->buscarPreguntasResponidasPorElUsuario($idUser);
                    $idsPreguntasNoRespondidas = array_values(array_diff($idsPreguntasTotales, $idsPreguntasRespondidas)); // con el array values lo reindexo x si me quedaron huecos entre meidio del array
                    $numPregunta = $this->model->seleccionarPreguntaAleatoriaQueElUserNoHayaRespondido($idsPreguntasNoRespondidas);
                }



                 //hay q agregar lo de la dificultad y lo de si la preg esta habilitada (un campo mas en la pregunta)




                $pregunta = $this->preguntaModel->obtenerPregunta($numPregunta);
                $this->model->crearNuevaPreguntaPartida($partida["id_partida"], $pregunta["id_pregunta"], $idUser);
            } else {
                $pregunta = $this->preguntaModel->obtenerPregunta($preguntaPartida["id_pregunta"]);
            }

            $categoria = $this->model->obtenerCategoria($pregunta["nombre"]);
            $respuestas = $this->preguntaModel->desordenarRespuestas($this->preguntaModel->obtenerRespuestas($pregunta["id_pregunta"]));
            $_SESSION['respuestas_desordenadas'] = $respuestas;

            $data["pregunta"] = $pregunta;
            $data["opciones"] = $respuestas;
            $data["categoria"] = $categoria;
            $data["esDeCorreccion"] = false;
        } else {
            $id_pregunta = $_POST["id_pregunta"];
            $id_respuestaSeleccionada = $_POST["respuesta"];
            $id_respuestaCorrecta = $this->preguntaModel->obtenerRespuestaCorrectaDeEstaPregunta($id_pregunta)["id_respuesta"];

            $pregunta = $this->preguntaModel->obtenerPregunta($id_pregunta);
            $data["pregunta"] = $pregunta;

            $respuestas = $_SESSION['respuestas_desordenadas'];
            foreach ($respuestas as &$respuesta) {
                $respuesta['seleccionada'] = ($respuesta['id_respuesta'] == $id_respuestaSeleccionada);
            }
            $data["opciones"] = $respuestas;
            $data["categoria"] = $this->model->obtenerCategoria($pregunta["nombre"]);
            $data["laSeleccionadaEsCorrecta"] = ($id_respuestaSeleccionada == $id_respuestaCorrecta);
            $data["esDeCorreccion"] = true;

            $pregunta["apariciones"] += 1;
            $preguntaPartida = $this->model->buscarUltimaPreguntaNoResponididaDeLaPartida($partida["id_partida"]);
            $preguntaPartida["id_preguntaPartida"] = $preguntaPartida["id_preguntaPartida"];
            $preguntaPartida["respondida"] = true;

            if ($data["laSeleccionadaEsCorrecta"]) {
                $pregunta["aciertos"] += 1;
                $preguntaPartida["acertoElUsuario"] = true;
                $partida["puntaje"] = isset($partida["puntaje"]) ? $partida["puntaje"] + 10 : 10;
            } else {
                $preguntaPartida["acertoElUsuario"] = false;
                $partida["terminada"] = true;
                $this->userModel->actualizarPuntaje($partida["puntaje"], $partida["id_usuario"]);
            }
            // aca deje comentado el actualizarPregunta, lo hice pq como probaba, iba a tener q modificar todas las preguntas jaj,
            // pero bueno, lo descomentamos y ya anda

            //$this->preguntaModel->actualizarPregunta($pregunta);
            $this->model->actualizaPartida($partida);
            $this->model->actualizaPreguntaPartida($preguntaPartida);
        }

        $this->presenter->show("partida", $data);
    }

    public function mostrarVistaReporte()
    {

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
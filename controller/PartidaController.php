<?php

class   PartidaController
{
    private $presenter;
    private $model;
    private $userModel;
    public function __construct($model, $userModel, $presenter)
    {
        $this->presenter = $presenter;
        $this->model = $model;
        $this->userModel = $userModel;
    }
    public function jugar() {
        if (!isset($_SESSION['user'])) {
            header("location: /acceso/ingresar");
            exit;
        }

        $data['user'] = $_SESSION['user'];
        $idUser = $this->userModel->obtenerIdUserPorUserName($_SESSION['user'])["id"];
        $data['idUsuario'] = $idUser;

        $partida = $this->model->buscarSiHayUnaPartidaEnCursoParaEsteUser($idUser);
        if ($partida === null) {
            $idPartidaABuscar = $this->model->crearPartidaEnCursoParaEsteUser($idUser);
            $partida = $this->model->buscarLaUltimaPartidaInsertada($idPartidaABuscar);
        }

        if (!isset($_POST["respuesta"])) {
            $preguntaPartida = $this->model->buscarUltimaPreguntaNoResponididaDeLaPartida($partida["id_partida"]);

            if ($preguntaPartida === null) {
                $numPregunta = random_int(1, 60); // Reemplazar por método que elija la próxima pregunta válida
                $pregunta = $this->model->obtenerPregunta($numPregunta);
                $this->model->crearNuevaPreguntaPartida($partida["id_partida"], $pregunta["id_pregunta"]);
                $preguntaPartida = $this->model->buscarUltimaPreguntaNoResponididaDeLaPartida($partida["id_partida"]);
            } else {
                $pregunta = $this->model->obtenerPregunta($preguntaPartida["id_pregunta"]);
            }

            $categoria = $this->model->obtenerCategoria($pregunta["nombre"]);
            $respuestas = $this->model->desordenarRespuestas($this->model->obtenerRespuestas($pregunta["id_pregunta"]));
            $_SESSION['respuestas_desordenadas'] = $respuestas;

            $data["pregunta"] = $pregunta;
            $data["opciones"] = $respuestas;
            $data["categoria"] = $categoria;
            $data["esDeCorreccion"] = false;
        } else {
            $id_pregunta = $_POST["id_pregunta"];
            $id_respuestaSeleccionada = $_POST["respuesta"];
            $id_respuestaCorrecta = $this->model->obtenerRespuestaCorrectaDeEstaPregunta($id_pregunta)["id_respuesta"];

            $pregunta = $this->model->obtenerPregunta($id_pregunta);
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
            }
            // aca faltaria actualizar la pregunta para que se le actualice el aciertos y apariciones
            // pero no lo hice pq como probaba, iba a tener q modificar todas las preguntas jaj, pero bueno
            // faltaria eso de actualizar la pregunta
            $this->model->actualizaPartida($partida);
            $this->model->actualizaPreguntaPartida($preguntaPartida);
        }

        $this->presenter->show("partida", $data);
    }
}
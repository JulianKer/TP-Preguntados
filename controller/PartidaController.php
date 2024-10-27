<?php

class   PartidaController
{
    private $presenter;
    private $model;
    public function __construct($model, $presenter)
    {
        $this->presenter = $presenter;
        $this->model = $model;
    }
    public function pregunta(){
        if (!isset($_SESSION['user'])) {
            header("location: /acceso/ingresar");
        }
        $data['user'] = $_SESSION['user'];
        $data['idUsuario'] = isset($_SESSION['idUsuario']);

        if (isset($_POST["respuesta"])){
            $id_pregunta = $_POST["id_pregunta"];
            $id_respuestaSeleccionada = $_POST["respuesta"];
            $id_respuestaCorrecta = $this->model->obtenerRespuestaCorrectaDeEstaPregunta($id_pregunta)["id_respuesta"];

            $pregunta = $this->model->obtenerPregunta($id_pregunta);
            $data["pregunta"] = $pregunta;
            $data["opciones"] = $this->model->desordenarRespuestas($this->model->obtenerRespuestas($id_pregunta));
            $data["categoria"] = $this->model->obtenerCategoria($pregunta["nombre"]);

            if ($id_respuestaSeleccionada == $id_respuestaCorrecta) {
                $data["laSeleccionadaEsCorrecta"] = true;
            }else{
                $data["laSeleccionadaEsCorrecta"] = false;
            }
            $data["esDeCorreccion"] = true;
        }else{
            $numPreguntaRandom = random_int(1, 60); // este desp hay q reemplazarlo por las validaciones para saber q pregunta mostrar segun la dificultad, respondida o no, etc, pero ahora hago un random (los parametros q llega al random seria el .lengh de cant de preguntas en la bdd)
            $pregunta = $this->model->obtenerPregunta($numPreguntaRandom);
            $categoria = $this->model->obtenerCategoria($pregunta["nombre"]);
            $respuestas = $this->model->desordenarRespuestas($this->model->obtenerRespuestas($pregunta["id_pregunta"]));

            $data["pregunta"] = $pregunta;
            $data["opciones"] = $respuestas;
            $data["categoria"] = $categoria;
            $data["esDeCorreccion"] = false;
        }
        $this ->presenter ->show("partida", $data);
    }
}
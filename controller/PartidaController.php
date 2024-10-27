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

        if (!isset($_POST["respuesta"])) {
            $numPreguntaRandom = random_int(1, 60);
            $pregunta = $this->model->obtenerPregunta($numPreguntaRandom);
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

            $respuestas = $_SESSION['respuestas_desordenadas']; // y aca puse q lo reciba por sesion pa q se mantenga el orden en q se mostraron las respuestas pq sino se desordenaban de nuevo
            foreach ($respuestas as &$respuesta) {
                $respuesta['seleccionada'] = ($respuesta['id_respuesta'] == $id_respuestaSeleccionada); // al array le agrego esta variable para saber cua pintar de rojo por si es q toque la incorrecta
            }
            $data["opciones"] = $respuestas;

            $data["categoria"] = $this->model->obtenerCategoria($pregunta["nombre"]);
            $data["laSeleccionadaEsCorrecta"] = ($id_respuestaSeleccionada == $id_respuestaCorrecta); //este lo uso pa saber q boton mostrar si el de home pq perdiste o el de siguiente
            $data["esDeCorreccion"] = true;
        }
        $this ->presenter ->show("partida", $data);
    }
}
<?php

class PreguntaModel
{
    private $database;
    public function __construct($database)
    {
        $this->database = $database;
    }


    public function obtenerPregunta($numPreguntaRandom){
        return $this->database->obtenerPregunta($numPreguntaRandom);
    }
    public function obtenerRespuestas($idPregunta){
        return $this->database->obtenerRespuestas($idPregunta);
    }
    public function obtenerRespuestaCorrectaDeEstaPregunta($id_pregunta){
        return $this->database->obtenerRespuestaCorrectaDeEstaPregunta($id_pregunta);
    }

    public function crearEInsertarRespuestasParaPreguntaCreada($idDePreguntaInsertada, $opcion1, $opcion2, $opcion3, $opcion4, $respuesta_correcta){
        $this->database->crearEInsertarRespuestasParaPreguntaCreada($idDePreguntaInsertada, $opcion1, $opcion2, $opcion3, $opcion4, $respuesta_correcta);
    }

    public function dameLaDescripcionDeLaRespuestaCorrectaSegunEstasOpciones($respuesta_correcta, $opcion1, $opcion2, $opcion3, $opcion4){
        switch ($respuesta_correcta) {
            case "opcion1":
                return $opcion1;
                case "opcion2":
                    return $opcion2;
                    case "opcion3":
                        return $opcion3;
            default:
                return $opcion4;
        }
    }

    public function setearEstaRespuestaComoCorrectaParaEstaPregunta($idDePreguntaInsertada, $descripcionDeLaRespuestaCorrecta){
        $this->database->setearEstaRespuestaComoCorrectaParaEstaPregunta($idDePreguntaInsertada, $descripcionDeLaRespuestaCorrecta);
    }

    public function desordenarRespuestas($arrayADesordenar){
        shuffle($arrayADesordenar);
        return $arrayADesordenar;
    }
    public function actualizarPregunta($pregunta){
        return $this->database->actualizarPregunta($pregunta);
    }

    public  function obtenerCantidadTotalDePreguntasQueExisten(){
        return $this->database->obtenerCantidadTotalDePreguntasQueExisten();
    }

    public function obtenerIdsDeTodasLasPreguntasQueExisten(){
        return $this->database->obtenerIdsDeTodasLasPreguntasQueExisten();
    }

    public function actualizarEstadoPregunta($id_pregunta, $id_estado){
        return $this->database->actualizarEstadoPregunta($id_pregunta, $id_estado);
    }

    public function crearReporte($id_pregunta, $id_usuario, $descripcion){
        $this -> database -> crearReporte($id_pregunta, $id_usuario, $descripcion);
        // faltaria guardar la descripcion del reporte en la base de datos pero despues lo agrego.
    }

    public function crearEInsertarNuevaPreguntaSugeridaYDevolverElidConElQueSeInserto($categoria, $pregunta){
        return $this->database->crearEInsertarNuevaPreguntaSugeridaYDevolverElidConElQueSeInserto($categoria, $pregunta);
    }
}


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
}
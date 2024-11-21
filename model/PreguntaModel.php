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

    public function editarRespuesta($idOpcionAActualizar, $valorDeLaOpcionAActualizar){
        $this->database->editarRespuesta($idOpcionAActualizar, $valorDeLaOpcionAActualizar);
    }

    public function obtenerRespuestas($idPregunta){
        return $this->database->obtenerRespuestas($idPregunta);
    }

    public function obtenerEstasRespuestasConNumeroDeOpcion($respuestas){
        for ($i=0; $i < count($respuestas); $i++) {
            $respuestas[$i]['numeroDeOpcion'] = ($i+1);
        }
        return $respuestas;
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

    public function actualizarPreguntaEditada($idPregunta, $pregunta, $categoria, $idEstado){
        $preguntaEncontrada = $this->obtenerPregunta($idPregunta);

        if (!$preguntaEncontrada) {
            return false;
        }
        $this->database->actualizarPreguntaEditada($idPregunta, $pregunta, $categoria, $idEstado);
        return true;
    }

    public  function obtenerCantidadTotalDePreguntasQueExisten(){
        return $this->database->obtenerCantidadTotalDePreguntasQueExisten();
    }

    public function obtenerIdsDeTodasLasPreguntasQueExisten(){
        return $this->database->obtenerIdsDeTodasLasPreguntasQueExisten();
    }

    public function obtenerTodasLasPreguntasDeLaTablaAprobadasYDesactivadas(){
        $preguntasRecibidas = $this->database->obtenerTodasLasPreguntasDeLaTablaAprobadasYDesactivadas();

        for ($i = 0; $i < count($preguntasRecibidas); $i++) {
            if ($preguntasRecibidas[$i]["id_estado"] === 1) { // Si está "desactivada", le pongo el botón de "habilitar"
                $preguntasRecibidas[$i]["imagenDelBoton"] = "checkIcon";
                $preguntasRecibidas[$i]["accion"] = "habilitar";
            }
            if ($preguntasRecibidas[$i]["id_estado"] === 4) { // Sino, te pongo el botón de desactivar
                $preguntasRecibidas[$i]["imagenDelBoton"] = "cancelIcon";
                $preguntasRecibidas[$i]["accion"] = "desactivar";
            }
        }
        //var_dump($preguntasRecibidas);
        return $preguntasRecibidas;
    }

    public function obtenerDesactivadas(){
        return $this->database->obtenerDesactivadas();
    }

    public function obtenerHabilitadas(){
        return $this->database->obtenerHabilitadas();
    }

    public function actualizarEstadoPregunta($id_pregunta, $id_estado){
        return $this->database->actualizarEstadoPregunta($id_pregunta, $id_estado);
    }

    public function crearReporte($id_pregunta, $id_usuario, $descripcion){
        $this -> database -> crearReporte($id_pregunta, $id_usuario, $descripcion);
        // faltaria guardar la descripcion del reporte en la base de datos pero despues lo agrego.
    }

    public function crearEInsertarNuevaPreguntaSugeridaYDevolverElidConElQueSeInserto($categoria, $pregunta, $idEstadoQueDebeQuedarLaPregunta){
        return $this->database->crearEInsertarNuevaPreguntaSugeridaYDevolverElidConElQueSeInserto($categoria, $pregunta, $idEstadoQueDebeQuedarLaPregunta);
    }

    public function desactivarPregunta($idDePreguntaADesactivar){
        if ($idDePreguntaADesactivar <= 0 || $idDePreguntaADesactivar > $this->obtenerCantidadTotalDePreguntasQueExisten()) {
            return "Esa pregunta no existe, no se puede desactivar.";
        }

        $pregunta = $this->obtenerPregunta($idDePreguntaADesactivar);

        if ($pregunta != null && $pregunta["estado"] === 4) {
            $this->database->cambiarEstadoDePregunta($idDePreguntaADesactivar, 1);
            return "Pregunta " . $idDePreguntaADesactivar . " desactivada correctamente.";
        }else{
            return "No se pudo desactivar la pregunta " . $idDePreguntaADesactivar . ".";
        }
    }

    public function habilitarPregunta($idDePreguntaAHabilitar){
        if ($idDePreguntaAHabilitar <= 0 || $idDePreguntaAHabilitar > $this->obtenerCantidadTotalDePreguntasQueExisten()) {
            return "Esa pregunta no existe, no se puede habilitar.";
        }

        $pregunta = $this->obtenerPregunta($idDePreguntaAHabilitar);

        if ($pregunta != null && $pregunta["estado"] === 1) {
            $this->cambiarEstadoDePregunta($idDePreguntaAHabilitar, 4);
            return "Pregunta " . $idDePreguntaAHabilitar . " habilitada correctamente.";
        }else{
            return "No se pudo habilitar la pregunta " . $idDePreguntaAHabilitar . ".";
        }
    }

    public function cambiarEstadoDePregunta($idDePreguntaACambiar, $idEstado){
        $this->database->cambiarEstadoDePregunta($idDePreguntaACambiar, $idEstado);
    }

    public function obtenerTodasSugeridas(){
        return $this->database->obtenerTodasSugeridas();
    }

    public function obtenerPreguntaSugerida($idPreguntaSugerida){
        return $this->database->obtenerPreguntaSugerida($idPreguntaSugerida);
    }

    public function aprobarSugerencia($idPreguntaSugerida){
        $this->database->aprobarSugerencia($idPreguntaSugerida);
    }

    public function rechazarSugerencia($idPreguntaSugerida){
        $this->database->rechazarSugerencia($idPreguntaSugerida);
    }
}


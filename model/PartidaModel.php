<?php

class PartidaModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function iniciarPartida ($jugador)
    {
        $sql = "insert into 
                    partida (id_jugador_1)
                values
                    ($jugador)";

        $this->database->query($sql);

        return $this->database->last_insert();
    }

    public function obtenerUnaPreguntaPorId($filter)
    {
        $sql = "select pregunta.*, categoria.nombre as categoria from pregunta 
                JOIN categoria on pregunta.id_categoria = categoria.id
                where pregunta.id = $filter
                order by rand() limit 1";

        $resultado = $this->database->query($sql);

        return $resultado[0];
    }
    public function obtenerRespuestasDeUnaPregunta($idPregunta)
    {
        $sql = "SELECT * FROM respuesta WHERE id_pregunta = $idPregunta";

        return $this->database->query($sql);
    }


}
<?php

class PartidaModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }


    public function obtenerPregunta($numPreguntaRandom){
        $sql = "SELECT * FROM pregunta p JOIN categoria c on p.id_categoria = c.id_categoria WHERE p.id_pregunta = $numPreguntaRandom";
        return $this->database->queryAssoc($sql);
    }

    public function obtenerRespuestas($idPregunta){
        $sql = "SELECT * FROM respuesta WHERE id_pregunta = $idPregunta";
        return $this->database->query($sql);
    }

    public function obtenerCategoria($categoria){
        // hago este para q me coincidan las clases con el css pq algunas categorias
        // estan separadas por 2 palabras, tonces asi la hago minuscula y las q son 2, las dejo en 1

        $categoriaADevolver = strtolower($categoria);
        if ($categoriaADevolver == "cultura general"){
            $categoriaADevolver = "cultura";
        }

        if ($categoriaADevolver == "formula 1"){
            $categoriaADevolver = "f1";
        }
        return $categoriaADevolver;
    }

    public function obtenerRespuestaCorrectaDeEstaPregunta($id_pregunta){
        $sql = "SELECT * FROM respuesta WHERE id_pregunta = $id_pregunta AND correcta = true";
        return $this->database->queryAssoc($sql);
    }

    public function desordenarRespuestas($arrayOrdenadoDeRespuestas){
        // preguntar al profe si desrodenarlo aca o hacer el insert con las resp desordenadas
        return $arrayOrdenadoDeRespuestas; // esto cambiarlo pq deje el metodo pero devuelvo lo mimso pq no se como desordenarlo
    }












    /*public function iniciarPartida ($jugador)
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
    }*/
}
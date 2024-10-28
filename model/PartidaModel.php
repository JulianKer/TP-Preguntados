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
        $resultado = $this->database->query($sql);
        return $resultado;
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

    public function desordenarRespuestas($arrayADesordenar){
        shuffle($arrayADesordenar);
        return $arrayADesordenar;
    }

    public  function crearPartidaEnCursoParaEsteUser($idUser){
        $sql = "INSERT INTO partida (id_usuario, puntaje, terminada) VALUES ( $idUser, 0, FALSE);";
        return $this->database->insertar($sql);
    }

    public function buscarSiHayUnaPartidaEnCursoParaEsteUser($idUser){
        $sql = "SELECT * FROM partida WHERE id_usuario = $idUser AND terminada = 0";
        return $this->database->queryAssoc($sql);
    }

    public function buscarUltimaPreguntaNoResponididaDeLaPartida($idPartida){
        $sql = "SELECT * FROM preguntapartida WHERE id_partida = $idPartida AND respondida = false LIMIT 1";
        return $this->database->queryAssoc($sql);
    }

    public function actualizaPartida($partida){
        $puntaje = $partida['puntaje'];
        $idPartida = $partida['id_partida'];
        $terminada = $partida['terminada'];

        $sql = "UPDATE `partida` SET `puntaje`= $puntaje,`terminada`= $terminada  WHERE `id_partida`= $idPartida";
        return $this->database->actualizar($sql);
    }

    public function crearNuevaPreguntaPartida($idPartida, $idPregunta) {
        $sql = "INSERT INTO preguntapartida (id_pregunta, id_partida, respondida, acertoElUsuario) VALUES ($idPregunta,$idPartida, 0, 0)";
        $this->database->actualizar($sql);
    }


    public function buscarUltimaPartidaPreguntaDeEstaPartida($idPartida){
        $ultimoIdInsertado = $this->database->getLastInsert();
        $sql = "SELECT * FROM preguntapartida where id_preguntaPartida = $ultimoIdInsertado AND id_partida = $ultimoIdInsertado";
        return $this->database->queryAssoc($sql);
    }
    public function actualizaPreguntaPartida($preguntaPartida){
        $idPreguntaPartida = $preguntaPartida['id_preguntaPartida'] ?? null;
        $respondida = isset($preguntaPartida['respondida']) ? (int)$preguntaPartida['respondida'] : null;
        $acertoElUsuario = isset($preguntaPartida['acertoElUsuario']) ? (int)$preguntaPartida['acertoElUsuario'] : null;

        if ($idPreguntaPartida !== null && $respondida !== null && $acertoElUsuario !== null) {
            $sql = "UPDATE `preguntapartida` SET `respondida` = $respondida, `acertoElUsuario` = $acertoElUsuario WHERE `id_preguntaPartida` = $idPreguntaPartida";
            $this->database->actualizar($sql);
        } else {
            echo "Faltan datos: ";
            echo "ID: " . ($idPreguntaPartida ?? "null") . " ";
            echo "RESP: " . ($respondida ?? "null") . " ";
            echo "ACIER: " . ($acertoElUsuario ?? "null") . " ";
        }
    }


    public function buscarLaUltimaPartidaInsertada($idPartidaABuscar){
         $sql = "SELECT * FROM partida WHERE id_partida = $idPartidaABuscar";
         return $this->database->queryAssoc($sql);
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
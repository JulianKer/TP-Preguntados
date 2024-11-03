<?php

class PartidaModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
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

    public  function crearPartidaEnCursoParaEsteUser($idUser){
        return $this->database->crearPartidaEnCursoParaEsteUser($idUser);
    }

    public function buscarSiHayUnaPartidaEnCursoParaEsteUser($idUser){
        return $this->database->buscarSiHayUnaPartidaEnCursoParaEsteUser($idUser);
    }

    public function buscarUltimaPreguntaNoResponididaDeLaPartida($idPartida){
        return $this->database->buscarUltimaPreguntaNoResponididaDeLaPartida($idPartida);
    }

    public function actualizaPartida($partida){
        return $this->database->actualizaPartida($partida);
    }

    public function crearNuevaPreguntaPartida($idPartida, $idPregunta, $idUser) {
        return $this->database->crearNuevaPreguntaPartida($idPartida, $idPregunta, $idUser);
    }

    /*public function buscarUltimaPartidaPreguntaDeEstaPartida($idPartida){ este metodo CREO que nunca lo uso, nose pq esta pq en el partida controler ni en ningun lado lo uso, lo habre creado y no lo borre pero por las dudas lo dejo y desp lo chekeo bien
        $ultimoIdInsertado = $this->database->getLastInsert();
        $sql = "SELECT * FROM preguntapartida where id_preguntaPartida = $ultimoIdInsertado AND id_partida = $ultimoIdInsertado";
        return $this->database->queryAssoc($sql);
    }*/

    public function actualizaPreguntaPartida($preguntaPartida){
        return $this->database->actualizaPreguntaPartida($preguntaPartida);
    }

    public function buscarLaUltimaPartidaInsertada($idPartidaABuscar){
        return $this->database->buscarLaUltimaPartidaInsertada($idPartidaABuscar);
    }

    public function buscarPreguntasResponidasPorElUsuario($idUser){
        return $this->database->buscarPreguntasResponidasPorElUsuario($idUser);
    }

    public function seleccionarPreguntaAleatoriaQueElUserNoHayaRespondido($idsPreguntasNoRespondidas){
        if (!empty($idsPreguntasNoRespondidas)) {
            $randomIndex = array_rand($idsPreguntasNoRespondidas);
            return $idsPreguntasNoRespondidas[$randomIndex];
        }
        return 0;
    }

    public function resetearPreguntaPartidaDeLasPreguntasRespondidasPorEsteUsuario($idUser){
        return $this->database->resetearPreguntaPartidaDeLasPreguntasRespondidasPorEsteUsuario($idUser);
    }



}
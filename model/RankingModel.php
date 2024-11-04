<?php

class RankingModel{

    private $database;
    private $modelUsuario;
    public function __construct($database, $modelUsuario){
        $this->database = $database;
        $this->modelUsuario = $modelUsuario;
    }

    public function dameElRanking(){
        $todosLosUsuarios = $this->modelUsuario->obtenerTodosLosUsuarios();

        $rankingADebolver = [];
        $i = 0;
        foreach ($todosLosUsuarios as $usuario){
            if ($i < 50){
                $mejorPartida = $this->obtenerMejorPartidadelUsuario($usuario["id"]);
                if ($mejorPartida) {
                    $rankingADebolver[$i] = $mejorPartida;
                    $i++;
                }
            }
        }
        $rankingADebolver = array_filter($rankingADebolver, function($item) {return is_array($item);});
        $puntajes = array_column($rankingADebolver, 'puntaje');
        array_multisort($puntajes, SORT_DESC, $rankingADebolver);
        //print_r($rankingADebolver);

        foreach ($rankingADebolver as $index => &$partida) {
            // PREGUNTAR: para diferenciar si hay empates en puntajes, podemos ordenarlo por quien consiguio primero ese puntaje,
            // es decir, por el id de la partida enotneces, el de id_partida menor, es el que queda arriba pq consiguio antes
            // el puntaje. VER LO DEL INDEX como lo ponemos si hay empate
            $partida['posicion'] = $index + 1; // con este le agrego un atributo posicion para poder mostrala en el mustahce
        }
        return $rankingADebolver;
    }
    public function obtenerMejorPartidadelUsuario($idUsuario){
        return $this->database->obtenerMejorPartidadelUsuario($idUsuario);
    }

    public function dameLaPosicionEnElRankingDeEsteUsuario($idUsuario){
        $posicion = 0;

        foreach ($this->dameElRanking() as $index => &$usuario) {
            if ($usuario["id"] == $idUsuario){
                $posicion = $index+1;
            }
        }
        return $posicion;
    }
}
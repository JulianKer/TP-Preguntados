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

        /* este antes SOLO los ordenaba por puntaje pero como le agregué lo del empate, lo comento pero lo dejo x las dudas
        $rankingADebolver = array_filter($rankingADebolver, function($item) {return is_array($item);});
        $puntajes = array_column($rankingADebolver, 'puntaje');
        array_multisort($puntajes, SORT_DESC, $rankingADebolver);*/

        usort($rankingADebolver, function($a, $b) {
            if ($a['puntaje'] != $b['puntaje']) {
                return $b['puntaje'] - $a['puntaje'];
            }
            return $a['id_partida'] - $b['id_partida'];
        });

        foreach ($rankingADebolver as $index => &$partida) {
            $partida['posicion'] = $index + 1; // con este le pongo un atributo posicion para poder mostralo en el mustahce
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
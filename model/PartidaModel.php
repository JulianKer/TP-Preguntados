<?php

class PartidaModel
{
    private $database;
    private $preguntaModel;
    private $userModel;
    public function __construct($database, $preguntaModel, $userModel){
        $this->database = $database;
        $this->preguntaModel = $preguntaModel;
        $this->userModel = $userModel;
    }

    public function obtenerPartidaEnCursoDelUserOCrearUnaNuevaPartida($idUser){
        $partida = $this->buscarSiHayUnaPartidaEnCursoParaEsteUser($idUser);
        if ($partida === null) {
            $idPartidaABuscar = $this->crearPartidaEnCursoParaEsteUser($idUser);
            $partida = $this->buscarLaUltimaPartidaInsertada($idPartidaABuscar);
        }
        return $partida;
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
        $fechaActual = new DateTime();
        $fechaParaGuardar = $fechaActual->format('Y-m-d H:i:s');

        return $this->database->crearNuevaPreguntaPartida($idPartida, $idPregunta, $idUser, $fechaParaGuardar);
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

    public function obtenerPartidasDelUsuario($idUser){
        return $this->database->obtenerPartidasDelUsuario($idUser);
    }

    public function contestoEnTiempoYForma($preguntaPartida){
        $fechaEntregada = new DateTime($preguntaPartida["fechaEntregada"]);
        $fechaActual = new DateTime();

        $diferencia = $fechaActual->getTimestamp() - $fechaEntregada->getTimestamp();

        if (abs($diferencia) <= 10) {
            return true;
        }else{
            return false;
        }
    }

    public function obtenerCuantoTiempoLeQuedaParaResponderLaPregunta($fechaDeEntrega){
        $fechaEntregada = new DateTime($fechaDeEntrega);
        $fechaActual = new DateTime();
        $tiempoRestante = 10 - ($fechaActual->getTimestamp() - $fechaEntregada->getTimestamp());
        return $tiempoRestante;
        //return abs($fechaActual->getTimestamp() - $fechaEntregada->getTimestamp());
    }

    // aca esta la logica q tenia en el partidaController, la pase a este model pq seria mi logica del negocio
    public function entregarPregunta($data, $sessionData) {
        $idUser = $data['idUsuario'];
        $partida = $data['partida'];

        $preguntaPartida = $this->buscarUltimaPreguntaNoResponididaDeLaPartida($partida["id_partida"]);

        if ($preguntaPartida === null) {
            $idsPreguntasRespondidas = $this->buscarPreguntasResponidasPorElUsuario($idUser);
            $idsPreguntasTotales = $this->preguntaModel->obtenerIdsDeTodasLasPreguntasQueExisten();
            $idsPreguntasNoRespondidas = array_values(array_diff($idsPreguntasTotales, $idsPreguntasRespondidas));
            $numPregunta = $this->seleccionarPreguntaAleatoriaQueElUserNoHayaRespondido($idsPreguntasNoRespondidas);

            if ($numPregunta === 0) {
                $this->resetearPreguntaPartidaDeLasPreguntasRespondidasPorEsteUsuario($idUser);
                $idsPreguntasRespondidas = $this->buscarPreguntasResponidasPorElUsuario($idUser);
                $idsPreguntasNoRespondidas = array_values(array_diff($idsPreguntasTotales, $idsPreguntasRespondidas));
                $numPregunta = $this->seleccionarPreguntaAleatoriaQueElUserNoHayaRespondido($idsPreguntasNoRespondidas);
            }


            //hay q agregar lo de la dificultad y lo de si la preg esta habilitada y reportada (pq aunq este reportada la puedo omostrar igual)



            $pregunta = $this->preguntaModel->obtenerPregunta($numPregunta);
            $this->crearNuevaPreguntaPartida($partida["id_partida"], $pregunta["id_pregunta"], $idUser);
            $preguntaPartida = $this->buscarUltimaPreguntaNoResponididaDeLaPartida($partida["id_partida"]);
        } else {
            $pregunta = $this->preguntaModel->obtenerPregunta($preguntaPartida["id_pregunta"]);
        }

        $categoria = $this->obtenerCategoria($pregunta["nombre"]);
        $respuestas = $this->preguntaModel->desordenarRespuestas($this->preguntaModel->obtenerRespuestas($pregunta["id_pregunta"]));
        $tiempoRestante = $this->obtenerCuantoTiempoLeQuedaParaResponderLaPregunta($preguntaPartida["fechaEntregada"]);

        return [
            "pregunta" => $pregunta,
            "opciones" => $respuestas,
            "categoria" => $categoria,
            "esDeCorreccion" => false,
            "mostrarReloj" => true,
            "tiempoRestante" => $tiempoRestante
        ];
    }

    public function procesarRespuesta($data, $postData, $sessionData) {
        $id_pregunta = $postData['id_pregunta'];
        $id_respuestaSeleccionada = $postData['respuesta'];
        $id_respuestaCorrecta = $this->preguntaModel->obtenerRespuestaCorrectaDeEstaPregunta($id_pregunta)["id_respuesta"];

        $pregunta = $this->preguntaModel->obtenerPregunta($id_pregunta);
        $respuestas = $sessionData['respuestas_desordenadas'];

        foreach ($respuestas as &$respuesta) {
            $respuesta['seleccionada'] = ($respuesta['id_respuesta'] == $id_respuestaSeleccionada);
        }

        $preguntaPartida = $this->buscarUltimaPreguntaNoResponididaDeLaPartida($data['partida']["id_partida"]);
        $preguntaPartida["respondida"] = true;

        $contestoDentroDelTiempo = $this->contestoEnTiempoYForma($preguntaPartida) === true;

        if ($contestoDentroDelTiempo) {
            if ($id_respuestaSeleccionada == $id_respuestaCorrecta) {
                $pregunta["aciertos"] += 1;
                $preguntaPartida["acertoElUsuario"] = true;
                $data['partida']["puntaje"] = isset($data['partida']["puntaje"]) ? $data['partida']["puntaje"] + 10 : 10;
            } else {
                $preguntaPartida["acertoElUsuario"] = false;
                $data['partida']["terminada"] = true;
                $this->userModel->actualizarPuntaje($data['partida']["puntaje"], $data['partida']["id_usuario"]);
            }
        } else {
            $preguntaPartida["acertoElUsuario"] = false;
            $data['partida']["terminada"] = true;
            $this->userModel->actualizarPuntaje($data['partida']["puntaje"], $data['partida']["id_usuario"]);
        }

        // aca deje comentado el actualizarPregunta, lo hice pq como probaba, iba a tener q modificar todas las preguntas jaj,
        // pero bueno, lo descomentamos y ya anda
        //$this->preguntaModel->actualizarPregunta($pregunta);
        $this->actualizaPartida($data['partida']);
        $this->actualizaPreguntaPartida($preguntaPartida);

        return [
            "pregunta" => $pregunta,
            "opciones" => $respuestas,
            "categoria" => $this->obtenerCategoria($pregunta["nombre"]),
            "laSeleccionadaEsCorrecta" => ($id_respuestaSeleccionada == $id_respuestaCorrecta),
            "esDeCorreccion" => true,
            "puntaje" => $data['partida']["puntaje"],
            "contestoDentroDelTiempo" => $contestoDentroDelTiempo
        ];
    }

}
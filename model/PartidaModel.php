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

    public function buscarPreguntasHabilitadasYOReportadasResponidasPorElUsuarioDeSuDificultad($idUser, $dificultadUser){
        return $this->database->buscarPreguntasHabilitadasYOReportadasResponidasPorElUsuarioDeSuDificultad($idUser, $dificultadUser);
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

    public function calcularDificultadUsuario($usuario){
        $dificultadNueva = $usuario["dificultad"];

        $resultado = ($usuario['aciertosusuario'] / $usuario['respondidasusuario']) * 100;

        if ($resultado >= 70) {
            $dificultadNueva = 3; // sos dificil (un pro)
        }else if ($resultado <= 30) {
            $dificultadNueva = 1; // sos facil (sos malisimo)
        }else{
            $dificultadNueva = 2; // sos normal (sos meh)
        }
        return $dificultadNueva;
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
        $userObj = $data['objUsuario'];
        $dificultadUser = $userObj['dificultad'];

        $preguntaPartida = $this->buscarUltimaPreguntaNoResponididaDeLaPartida($partida["id_partida"]);

        if ($preguntaPartida === null) {
            $idsPreguntasRespondidas = $this->buscarPreguntasHabilitadasYOReportadasResponidasPorElUsuarioDeSuDificultad($idUser, $dificultadUser);// a estos dos metodos hay que agregarles el "con este nivel del user"
            $idsPreguntasTotales = $this->preguntaModel->obtenerIdsDeTodasLasPreguntasHabilitadasYOReportadasQueExistenDeEstaDificultad($dificultadUser);// ----------
            $idsPreguntasNoRespondidas = array_values(array_diff($idsPreguntasTotales, $idsPreguntasRespondidas));
            $numPregunta = $this->seleccionarPreguntaAleatoriaQueElUserNoHayaRespondido($idsPreguntasNoRespondidas);

            if ($numPregunta === 0) {
                $this->resetearPreguntaPartidaDeLasPreguntasRespondidasPorEsteUsuario($idUser);
                $idsPreguntasRespondidas = $this->buscarPreguntasHabilitadasYOReportadasResponidasPorElUsuarioDeSuDificultad($idUser, $dificultadUser);
                $idsPreguntasNoRespondidas = array_values(array_diff($idsPreguntasTotales, $idsPreguntasRespondidas));
                $numPregunta = $this->seleccionarPreguntaAleatoriaQueElUserNoHayaRespondido($idsPreguntasNoRespondidas);
            }
            //hay q agregar lo de la dificultad y lo de si la preg esta habilitada y reportada (pq aunq este reportada la puedo omostrar igual)

            $pregunta = $this->preguntaModel->obtenerPregunta($numPregunta);
            $this->crearNuevaPreguntaPartida($partida["id_partida"], $pregunta["id_pregunta"], $idUser);
            $preguntaPartida = $this->buscarUltimaPreguntaNoResponididaDeLaPartida($partida["id_partida"]);
        } else {
            $pregunta = $this->preguntaModel->obtenerPregunta($preguntaPartida["id_pregunta"]);
        } //ANDAR ANDA, LO UNICO Q HAY Q HABILITAR TODAS LAS PREUGNTAS Y HACER LO DE DIVIDIRLAS EN 20 PREGS DIFICILES, 20 NORMALES Y 20 FACILES ASI NO SE QUEDA SIN PREGUNTAS AL PRINCIPIO

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
        $usuario = $data['objUsuario'];

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
                $usuario['aciertosusuario'] += 1;
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

        // actualizo usuario
        $usuario['respondidasusuario'] += 1;

        if ($usuario['respondidasusuario'] > 10){
            $usuario['dificultad'] = $this->calcularDificultadUsuario($usuario); // ACA las primeras 10 preguntas NO le va a cambiar la dificultad, x ende las priemras 10 van a ser faciles
        }
        $this->userModel->actualizarUsuario($usuario);

        // actualizo pregunta
        $pregunta['apariciones'] += 1; // aparicion se la actualizo si respondio bien o mal pq la pregunta se mostró igual, me da lo mismo
        $pregunta['dificultad'] = $this->preguntaModel->calcularDificultadDePregunta($pregunta);
        $this->preguntaModel->actualizarPregunta($pregunta);

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
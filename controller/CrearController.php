<?php

class CrearController{

    private $modelPregunta;
    private $modelUsuario;
    private $modelCategoria;
    private $presenter;

    public function __construct($modelPregunta, $modelUsuario, $modelCategoria, $presenter){
        $this->modelPregunta = $modelPregunta;
        $this->modelUsuario = $modelUsuario;
        $this->modelCategoria = $modelCategoria;
        $this->presenter = $presenter;
    }

    public function inicio(){
        header("location: /crear/pregunta");
    }
    public function pregunta(){

        if (!isset($_SESSION['user'])) { // validar que aca SOLO entren rol jugador (pq no puede sugerir preguntas los demas roles, o si?)
            header("location: /acceso/ingresar");
            exit();
        }
        $userEncontrado = $this->modelUsuario->obtenerUsuarioPorId($_SESSION['idUser'])[0];

        $data["musicaActivada"] = $userEncontrado["musica"];
        $data["objUsuario"] = $userEncontrado;
        $data['user'] = $_SESSION['user'];
        $data['idUsuario'] = $_SESSION['idUser'];


        if (isset($_SESSION["errorCrear"])){
            $data["errorCrear"] = $_SESSION["errorCrear"];
        }

        if (isset($_SESSION["exitoCrear"])){
            $data["exitoCrear"] = $_SESSION["exitoCrear"];
        }
        $data['categorias'] = $this->modelCategoria->obtenerTodasCategorias();

        $this->presenter->show("crearPregunta", $data);
    }


    public function validarPregunta(){
        if (!isset($_SESSION['user'])) {
            header("location: /acceso/ingresar");
            exit();
        }

        if (!isset($_POST["categoria"]) || empty($_POST["categoria"]) ||
            !isset($_POST["pregunta"]) || empty($_POST["pregunta"]) ||
            !isset($_POST["respuesta_correcta"]) || empty($_POST["respuesta_correcta"]) ||
            !isset($_POST["opcion1"]) || empty($_POST["opcion1"]) ||
            !isset($_POST["opcion2"]) || empty($_POST["opcion2"]) ||
            !isset($_POST["opcion3"]) || empty($_POST["opcion3"]) ||
            !isset($_POST["opcion4"]) || empty($_POST["opcion4"])){

            $_SESSION["errorCrear"] = "Los campos no deben estar vacíos: seleccione una categoria, formule la pregunta, escriba las posibles opciones y elija la opción correcta.";
            $_SESSION["exitoCrear"] = null;
            header("location: /crear/pregunta");
            exit();
        }

        $categoria = $_POST["categoria"];
        $pregunta = $_POST["pregunta"];
        $respuesta_correcta = $_POST["respuesta_correcta"];
        $opcion1 = $_POST["opcion1"];
        $opcion2 = $_POST["opcion2"];
        $opcion3 = $_POST["opcion3"];
        $opcion4 = $_POST["opcion4"];

        $idDePreguntaInsertada = $this->modelPregunta->crearEInsertarNuevaPreguntaSugeridaYDevolverElidConElQueSeInserto($categoria, $pregunta);

        if ($idDePreguntaInsertada == null){
            $_SESSION["errorCrear"] = "No se pudo crear la pregunta. Intente nuevamente.";
            $_SESSION["exitoCrear"] = null;
            header("location: /crear/pregunta");
            exit();
        }

        $this->modelPregunta->crearEInsertarRespuestasParaPreguntaCreada($idDePreguntaInsertada, $opcion1, $opcion2, $opcion3, $opcion4, $respuesta_correcta);

        $descripcionDeLaRespuestaCorrecta = $this->modelPregunta->dameLaDescripcionDeLaRespuestaCorrectaSegunEstasOpciones($respuesta_correcta, $opcion1, $opcion2, $opcion3, $opcion4);
        $this->modelPregunta->setearEstaRespuestaComoCorrectaParaEstaPregunta($idDePreguntaInsertada, $descripcionDeLaRespuestaCorrecta);

        $_SESSION["exitoCrear"] = "¡Pregunta creada con éxito! Nuestro staff evaluará tu sugerencia para incorporarla a QuizMaster. Gracias por tu aporte : )";
        $_SESSION["errorCrear"] = null;
        header("location: /crear/pregunta");
        exit();
    }


}
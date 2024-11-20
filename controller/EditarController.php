<?php

class EditarController{

    private $modelPregunta;
    private $modelUsuario;
    private $modelCategoria;
    private $modelReporte;
    private $presenter;

    public function __construct($modelPregunta, $modelUsuario, $modelCategoria, $modelReporte, $presenter){
        $this->modelPregunta = $modelPregunta;
        $this->modelUsuario = $modelUsuario;
        $this->modelCategoria = $modelCategoria;
        $this->modelReporte = $modelReporte;
        $this->presenter = $presenter;
    }

    public function inicio(){
        header("location: /principal/inicio");
    }
    public function nuevaPregunta(){

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

        // son muchas preguntas pq tengo muchos inputs que corroborar (tanto los hidden como los q se ven, x eso,
        // verifico si existen o si estan vacios cada uno de ellos)
        if (!isset($_POST["categoria"]) || empty($_POST["categoria"]) ||
            !isset($_POST["pregunta"]) || empty($_POST["pregunta"]) ||
            !isset($_POST["respuesta_correcta"]) || empty($_POST["respuesta_correcta"]) ||
            !isset($_POST["opcion1"]) || empty($_POST["opcion1"]) ||
            !isset($_POST["opcion2"]) || empty($_POST["opcion2"]) ||
            !isset($_POST["opcion3"]) || empty($_POST["opcion3"]) ||
            !isset($_POST["opcion4"]) || empty($_POST["opcion4"])){

            $_SESSION["errorCrear"] = "Los campos no deben estar vacíos: seleccione una categoria, formule la pregunta, escriba las posibles opciones y elija la opción correcta.";
            $_SESSION["exitoCrear"] = null;
            header("location: /editar/nuevaPregunta");
            exit();
        }

        $categoria = $_POST["categoria"];
        $pregunta = $_POST["pregunta"];
        $respuesta_correcta = $_POST["respuesta_correcta"];
        $opcion1 = $_POST["opcion1"];
        $opcion2 = $_POST["opcion2"];
        $opcion3 = $_POST["opcion3"];
        $opcion4 = $_POST["opcion4"];

        $idEstadoQueDebeQuedarLaPregunta = $_SESSION["usuarioObjetoDeLaSesion"]["rango"] === 2 ? 4 : 5; // si sos "editor" queda la pregunta con estado "aprobada" (4) directamente,  si sos "jugador", se inserta la preg con estado "pendiente" (5)

        $idDePreguntaInsertada = $this->modelPregunta->crearEInsertarNuevaPreguntaSugeridaYDevolverElidConElQueSeInserto($categoria, $pregunta, $idEstadoQueDebeQuedarLaPregunta);

        if ($idDePreguntaInsertada == null){
            $_SESSION["errorCrear"] = "No se pudo crear la pregunta. Intente nuevamente.";
            $_SESSION["exitoCrear"] = null;
            header("location: /editar/nuevaPregunta");
            exit();
        }

        $this->modelPregunta->crearEInsertarRespuestasParaPreguntaCreada($idDePreguntaInsertada, $opcion1, $opcion2, $opcion3, $opcion4, $respuesta_correcta);

        $descripcionDeLaRespuestaCorrecta = $this->modelPregunta->dameLaDescripcionDeLaRespuestaCorrectaSegunEstasOpciones($respuesta_correcta, $opcion1, $opcion2, $opcion3, $opcion4);
        $this->modelPregunta->setearEstaRespuestaComoCorrectaParaEstaPregunta($idDePreguntaInsertada, $descripcionDeLaRespuestaCorrecta);

        $_SESSION["exitoCrear"] = $_SESSION["usuarioObjetoDeLaSesion"]["rango"] === 2 ? "Pregunta creada correctamente." : "¡Pregunta creada con éxito! Nuestro staff evaluará tu sugerencia para incorporarla a QuizMaster. Gracias por tu aporte : )";
        $_SESSION["errorCrear"] = null;
        header("location: /editar/nuevaPregunta");
        exit();
    }


    public function pregunta(){
        $userEncontrado = $this->modelUsuario->obtenerUsuarioPorId($_SESSION['idUser'])[0];

        $data["musicaActivada"] = $userEncontrado["musica"];
        $data["objUsuario"] = $userEncontrado;
        $data['user'] = $_SESSION['user'];
        $data['idUsuario'] = $_SESSION['idUser'];

        $idPregunta = isset($_GET['var1']) ? $_GET['var1'] : 0;

        $pregunta = $this->modelPregunta->obtenerPregunta($idPregunta);

        if (!$pregunta){
            $_SESSION["errorEditar"] = "La pregunta que quieres editar no existe.";
            header("location: /principal/inicio");
            exit();
        }

        $data['pregunta'] = $pregunta;

        $categoriasRestantes = $this->modelCategoria->dameTodasLasCategoriasMenosLaQueTePaso($pregunta['id_categoria']);
        $data['categoriasRestantes'] = $categoriasRestantes;

        $respuestas = $this->modelPregunta->desordenarRespuestas($this->modelPregunta->obtenerRespuestas($pregunta["id_pregunta"]));
        $data['respuestas'] = $this->modelPregunta->obtenerEstasRespuestasConNumeroDeOpcion($respuestas);

        if (isset($_SESSION["errorCrear"])){
            $data["errorCrear"] = $_SESSION["errorCrear"];
        }

        if (isset($_SESSION["exitoCrear"])){
            $data["exitoCrear"] = $_SESSION["exitoCrear"];
        }

        $data['idReporteAEliminar'] = isset($_SESSION['idReporteAEliminar']) ? $_SESSION['idReporteAEliminar'] : "";
        $data['vengoDeReportar'] = isset($_SESSION['idReporteAEliminar']);

        $this->presenter->show("editarPregunta", $data);
    }

    public function validarPreguntaEditada(){

        // son muchas preguntas pq tengo muchos inputs que corroborar (tanto los hidden como los q se ven, x eso,
        // verifico si existen o si estan vacios cada uno de ellos)

        if (!isset($_POST["id_pregunta"]) || empty($_POST["id_pregunta"]) ||
            !isset($_POST["categoria"]) || empty($_POST["categoria"]) ||
            !isset($_POST["pregunta"]) || empty($_POST["pregunta"]) ||
            !isset($_POST["respuesta_correcta"]) || empty($_POST["respuesta_correcta"]) ||

            !isset($_POST["estadoDeLaPreguntaQueVieneDelPost"]) || empty($_POST["estadoDeLaPreguntaQueVieneDelPost"]) ||

            !isset($_POST["opcion1"]) || empty($_POST["opcion1"]) || !isset($_POST["idOpcion1"]) || empty($_POST["idOpcion1"]) ||
            !isset($_POST["opcion2"]) || empty($_POST["opcion2"]) || !isset($_POST["idOpcion2"]) || empty($_POST["idOpcion2"]) ||
            !isset($_POST["opcion3"]) || empty($_POST["opcion3"]) || !isset($_POST["idOpcion3"]) || empty($_POST["idOpcion3"]) ||
            !isset($_POST["opcion4"]) || empty($_POST["opcion4"]) || !isset($_POST["idOpcion4"]) || empty($_POST["idOpcion4"])  ) {

            $_SESSION["errorEditar"] = "Los campos no deben estar vacíos: seleccione una categoria, formule la pregunta, escriba las posibles opciones y elija la opción correcta.";
            $_SESSION["exitoEditar"] = null;
            header("location: /editar/pregunta/".$_POST["id_pregunta"]);
            exit();
        }

        $idPregunta = $_POST["id_pregunta"];
        $pregunta = $_POST["pregunta"];
        $categoria = $_POST["categoria"];
        $idEstado = 4; //(aprobada) le pongo este pq al acutualizarla, se supone que el editor aprueba re reporte actualizando correctamente la pregunta por ende ya le setea el estado de que ahora esta corregida
        $idEstado = isset($_POST["idReporteAEliminar"]) ? 4 : $_POST["estadoDeLaPreguntaQueVieneDelPost"];

        $seEditoLaPregunta = $this->modelPregunta->actualizarPreguntaEditada($idPregunta, $pregunta, $categoria, $idEstado);

        if (!$seEditoLaPregunta){
            $_SESSION["errorEditar"] = "No se pudo editar la pregunta. Intente nuevamente.";
            $_SESSION["exitoEditar"] = null;
            header("location: /editar/pregunta/".$idPregunta);
            exit();
        }

        // -- aca updateo las respuestas----
        $opcion1 = $_POST["opcion1"];
        $idOpcion1 = $_POST["idOpcion1"];
        $this->modelPregunta->editarRespuesta($idOpcion1, $opcion1);

        $opcion2 = $_POST["opcion2"];
        $idOpcion2 = $_POST["idOpcion2"];
        $this->modelPregunta->editarRespuesta($idOpcion2, $opcion2);

        $opcion3 = $_POST["opcion3"];
        $idOpcion3 = $_POST["idOpcion3"];
        $this->modelPregunta->editarRespuesta($idOpcion3, $opcion3);

        $opcion4 = $_POST["opcion4"];
        $idOpcion4 = $_POST["idOpcion4"];
        $this->modelPregunta->editarRespuesta($idOpcion4, $opcion4);
        // -----------------------------------


        // ---- aca le updateo la correcta a la respuesta q corresponde---
        $respuesta_correcta = $_POST["respuesta_correcta"];
        $descripcionDeLaRespuestaCorrecta = $this->modelPregunta->dameLaDescripcionDeLaRespuestaCorrectaSegunEstasOpciones($respuesta_correcta, $opcion1, $opcion2, $opcion3, $opcion4);

        $this->modelPregunta->setearEstaRespuestaComoCorrectaParaEstaPregunta($idPregunta, $descripcionDeLaRespuestaCorrecta);

        if (isset($_POST["idReporteAEliminar"])){
            $idReporteAEliminar = isset($_POST["idReporteAEliminar"]) ? $_POST["idReporteAEliminar"] : 0;
            $idReporteAEliminar > 0 ? $this->modelReporte->eliminarReporte($idReporteAEliminar) : "";
        }

        $_SESSION["exitoEditar"] = "Pregunta ".$idPregunta." editada con éxito.";
        $_SESSION["errorEditar"] = null;
        header("location: /principal/inicio");
        exit();
    }
}
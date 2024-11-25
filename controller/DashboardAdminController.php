<?php

class DashboardAdminController
{
    private $model;
    private $presenter;
    private $modelUsuario;

    private $modelPregunta;

    private $fpdf;
    private $modelDashboardAdmin;
    public function __construct($model, $modelUsuario,$modelPregunta, $presenter, $fpdf)
    {
        $this->model = $model;
        $this->modelUsuario = $modelUsuario;
        $this->modelPregunta = $modelPregunta;
        $this->presenter = $presenter;
        $this->fpdf = $fpdf;

    }
    public function inicio(){
        header("location: /principal/inicio");
    }
    public function obtenerDatos(){
        //  = $_GET["filtro"] ?? 5; aca deberia recibir un filtro, para despues filtrar por dia - mes - año - toda la historia
        // tendria que quedarme el dato de esta forma    $datosParaAjax["datosPais"] = $this->model->obtenerCantidadDeUsuariosPorPais($filtro);
        $datosParaAjax = [];


        $datosParaAjax["datosCantidadJugadores"] = $this->model->obtenerCantidadDeJugadores();
        $datosParaAjax["cantidadDePreguntas"] = $this->model->obtenerPreguntasHabilitadas();
        $datosParaAjax["test"] = 4;


        $datosParaAjax["cantidadJugadoresPorSexo"] = $this->model->obtenerCantidadDeJugadoresPorSexo();
        $datosParaAjax["cantidadDePartidasJugadasPorUsuario"] = $this->model->obtenerCantidadDePartidasJugadasPorUsuario();
        $datosParaAjax["cantidadDePreguntasPorCategoria"] = $this->model->obtenerCantidadDePreguntasPorCategoria();

        header('Content-Type: application/json');
        echo json_encode($datosParaAjax);
    }


    public function generarPdf()
    {
        $cantidadJugadores = $_POST['cantidadJugadores'];
        $cantidadPreguntas = $_POST['cantidadPreguntas'];
        $chart3 = $_POST['chart3'];
        $chart4 = $_POST['chart4'];
        $chart5 = $_POST['chart5'];

        // Valido que existan los datos que recupere del form
        if (!$cantidadJugadores || !$cantidadPreguntas || !$chart3 || !$chart4 || !$chart5) {
            die("Faltan datos para generar el PDF.");
        }
        // creo el pdf
        $pdfGenerador = new PdfGenerador();
        $pdfGenerador->generarPdf($cantidadJugadores, $cantidadPreguntas, $chart3, $chart4, $chart5);
    }

}
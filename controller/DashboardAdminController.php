<?php

class DashboardAdminController
{
    private $model;
    private $presenter;
    private $modelUsuario;

    private $modelPregunta;

    private $modelDashboardAdmin;
    public function __construct($model, $modelUsuario,$modelPregunta, $presenter)
    {
        $this->model = $model;
        $this->modelUsuario = $modelUsuario;
        $this->modelPregunta = $modelPregunta;
        $this->presenter = $presenter;


    }
    public function inicio(){
        header("location: /principal/inicio");
    }
    public function obtenerDatos(){
        //  = $_GET["filtro"] ?? 5; aca deberia recibir un filtro, para despues filtrar por dia - mes - año - toda la historia
        // tendria que quedarme el dato de esta forma    $datosParaAjax["datosPais"] = $this->model->obtenerCantidadDeUsuariosPorPais($filtro);


        $datosParaAjax = [];
        $datosParaAjax["datosCantidadJugadores"] = $this->model->obtenerCantidadDeJugadores();
        $datosParaAjax["test"] = 4;
        $datosParaAjax["cantidadJugadoresPorSexo"] = $this->model->obtenerCantidadDeJugadoresPorSexo();
        $datosParaAjax["cantidadDePreguntasPorCategoria"] = $this->model->obtenerCantidadDePreguntasPorCategoria();
        $datosParaAjax["cantidadDePartidasJugadasPorUsuario"] = $this->model->obtenerCantidadDePartidasJugadasPorUsuario();
        $datosParaAjax["cantidadDePreguntas"] = $this->model->obtenerPreguntasHabilitadas();

        header('Content-Type: application/json');  // Esto asegura que la respuesta sea en formato JSON
        echo json_encode($datosParaAjax);
    }
}
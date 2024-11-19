<?php

class DashboardAdminController
{
    private $model;
    private $presenter;
    private $modelUsuario;

    private $modelDashboardAdmin;
    public function __construct($model, $modelUsuario, $presenter)
    {
        $this->model = $model;
        $this->modelUsuario = $modelUsuario;
        $this->presenter = $presenter;


    }
    public function default(){
        $data = [];
        $this->presenter->show('dashboardAdmin', $data);
    }

    public function obtenerDatos(){
        //  = $_GET["filtro"] ?? 5; aca deberia recibir un filtro, para despues filtrar por dia - mes - año - toda la historia
        // tendria que quedarme el dato de esta forma    $datosParaAjax["datosPais"] = $this->model->obtenerCantidadDeUsuariosPorPais($filtro);


        $datosParaAjax = [];
        $datosParaAjax["datosCantidadJugadores"] = $this->model->obtenerCantidadDeJugadores();
        $datosParaAjax["test"] = 4;
        $datosParaAjax["cantidadJugadoresPorSexo"] = $this->model->obtenerCantidadDeJugadoresPorSexo();
        header('Content-Type: application/json');  // Esto asegura que la respuesta sea en formato JSON
        echo json_encode($datosParaAjax);
    }
}
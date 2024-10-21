<?php

class HomeController
{

    private $model;
    private $presenter;

    public function __construct($model, $presenter)
    {
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function inicio(){
        if (!isset($_SESSION['user'])) {
            header("location: /acceso/ingresar");
        }
        $data['user'] = $_SESSION['user'];
        $this->presenter->show('home', $data);
    }

    public function redirectHome()
    {
        header('location: /principal/inicio');
        exit();
    }
}
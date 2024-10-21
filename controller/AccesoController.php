<?php

class AccesoController
{
    private $presenter;
    private $model;

    public function __construct($model, $presenter) // ver pq deberia recibir tmb el model ya q necesito logica je
    {
        $this->presenter = $presenter;
        $this->model = $model;
    }

    public function ingresar(){
        $data = [];
        if (isset($_GET["msj"])){
            $data["msj"] = $_GET["msj"];
        }
        $this->presenter->show("login", $data);
    }

    public function registrar(){
        $this->presenter->show("register");
    }

    public function validarIngreso(){
        $user = $_POST['username'];
        $pass = $_POST['password'];

        $validation = $this->model->validate($user, $pass);

        if ($validation) {
            $_SESSION['user'] = $user;
            header('location: /pokedex/list');
            exit();
        }
        header('location: /acceso/ingresar?msj=' . urldecode("Usuario o contraseña incorrecta."));
        exit();
    }
}
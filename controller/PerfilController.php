<?php

class PerfilController{

    private $model;
    private $presenter;
    public function __construct($model,$presenter){
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function usuario(){
        $idDelUser = null;
        $data = null;

        if (!isset($_SESSION['user'])) {
            header("location: /acceso/ingresar");
            exit();
        }

        if (isset($_GET["var1"])){
            $idDelUser = $_GET["var1"]; //este lo hago por si envió un user para ver /x (mustro el perfil de ese user)
        }else{
            $idDelUser = $_SESSION['idUser'];// sino lo envió, mustro el user de la sesion (osea su propio perfil)
            $data["estoyEnMiPerfil"] = true;
        }

        $userEncontrado = $this->model->obtenerUsuarioPorId($idDelUser)[0];

        if ($userEncontrado == null){
            header("location: /principal/inicio");
            exit();
        }

        $data["coordenadas"] = $this->model->obtenerCoordenadas($userEncontrado["ubicacion"]);
        $data['usuario'] = $userEncontrado;
        $data['user'] = $_SESSION['user'];

        $this->presenter->show("perfil", $data);
    }
















}
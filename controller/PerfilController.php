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
            $idDelUser = $_GET["var1"]; //este lo hago por si recibo un user para ver /x (mustro el perfil de ese user)
        }else{
            $idDelUser = $_SESSION['idUser'];// sino lo recibo, mustro el user de la sesion (osea su propio perfil)
            $data["estoyEnMiPerfil"] = true;
        }

        $userEncontrado = $this->model->obtenerUsuarioPorId($idDelUser)[0];

        if ($userEncontrado == null){
            header("location: /principal/inicio");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["musica"])) {
            $activacion = $_POST["musica"] == "SI" ? 1 : 0;
            $this->model->setearMusicaActivadaDelUsuario($activacion, $idDelUser);
            header("location: /perfil/usuario");
            exit(); // lo habia intentado hacer por ajax pero tenia problemas con el .play del audio x eso directamente redirijo para q cargue tod y listo
        }
        $data["musicaActivada"] = $userEncontrado["musica"];

        $data["coordenadas"] = $this->model->obtenerCoordenadas($userEncontrado["ubicacion"]);
        $data['usuario'] = $userEncontrado;
        $data['user'] = $_SESSION['user'];

        $this->presenter->show("perfil", $data);
    }
}
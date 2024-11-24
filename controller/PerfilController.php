<?php

class PerfilController{

    private $model;
    private $rankingModel;
    private $partidasModel;
    private $presenter;
    public function __construct($model, $partidasModel, $rankingModel, $presenter){
        $this->model = $model;
        $this->rankingModel = $rankingModel;
        $this->partidasModel = $partidasModel;
        $this->presenter = $presenter;
    }

    public function inicio()
    {
        header('location: /perfil/usuario');
        exit();
    }
    public function usuario(){
        $idDelUser = null;
        $idDelUserAMostrar = null;
        $data = null;

        $ip = getHostByName(getHostName());

        if (isset($_GET["var1"])){
            $idDelUserAMostrar = $_GET["var1"]; //este lo hago por si recibo un user para ver /x (mustro el perfil de ese user)
            QrGenerador::generarYguardarQr("http://".$ip."/perfil/usuario/" . $idDelUserAMostrar);
        }else{
            $idDelUserAMostrar = $_SESSION['idUser']; //este lo hago por si recibo un user para ver /x (mustro el perfil de ese user)
            $data["estoyEnMiPerfil"] = true;
            QrGenerador::generarYguardarQr("http://".$ip."/perfil/usuario/" . $_SESSION["idUser"]);
        }

        $idDelUser = $_SESSION['idUser'] ?? null;

        $userEncontrado = isset($_SESSION['user']) ? $this->model->obtenerUsuarioPorId($idDelUser)[0] : null;
        $userAMostrar = $this->model->obtenerUsuarioPorId($idDelUserAMostrar)[0];

        if ($userAMostrar == null){
            header("location: /principal/inicio");
            exit();
        }
        $userAMostrar["posicionEnElRanking"] = $this->rankingModel->dameLaPosicionEnElRankingDeEsteUsuario($userAMostrar["id"]);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["musica"])) {
            $activacion = $_POST["musica"] == "SI" ? 1 : 0;
            $this->model->setearMusicaActivadaDelUsuario($activacion, $idDelUser);
            header("location: /perfil/usuario");
            exit();
        }else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["sonido"])){
            $activacion = $_POST["sonido"] == "SI" ? 1 : 0;
            $this->model->setearSonidoDelUsuario($activacion, $idDelUser);
            header("location: /perfil/usuario");
            exit();
        }

        $data["musicaActivada"] = $userEncontrado ? $userEncontrado["musica"] : "";
        $data["sonidoActivado"] = $userEncontrado ? $userEncontrado["sonido"] : "";
        $data['user'] = $userEncontrado ? $_SESSION['user'] : "";
        $data['objUsuario'] = $userEncontrado ?? null;

        $data['usuarioAMostrar'] = $userAMostrar;
        $data["coordenadas"] = $this->model->obtenerCoordenadas($userAMostrar["ubicacion"]);
        $data["partidasDelUsuario"] = $this->partidasModel->obtenerPartidasDelUsuario($userAMostrar["id"]);


        $data["esJugador"] = $this->model->saberSiEsJugador($userAMostrar["rango"]);
        $data["esEditor"] = $this->model->saberSiEsEditor($userAMostrar["rango"]);
        $data["esAdmin"] = $this->model->saberSiEsAdmin($userAMostrar["rango"]);

        $this->presenter->show("perfil", $data);
    }
}
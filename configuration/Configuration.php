<?php
include_once("helper/Database.php"); // esta es la nueva clase para usar stmt
include_once("helper/IncludeFilePresenter.php");
include_once("helper/Router.php");
include_once("helper/MustachePresenter.php");

include_once("controller/PrincipalController.php");
include_once("model/PrincipalModel.php");

include_once("controller/AccesoController.php"); //este lo unifique para hacer el register y login en el mismo acceso q usa el model de usuario
include_once("model/UsuarioModel.php");

include_once("controller/PerfilController.php");

include_once ("controller/PartidaController.php");
include_once ("model/PartidaModel.php");

include_once ("model/PreguntaModel.php");

include_once ("controller/RankingController.php");
include_once ("model/RankingModel.php");

include_once('vendor/mustache/src/Mustache/Autoloader.php');

class Configuration
{
    public function __construct()
    {
    }

    public function getPrincipalController(){
        return new PrincipalController($this->getPrincipalModel(), $this->getPartidaModel(), $this->getUsuarioModel(), $this->getRankingModel(), $this->getPresenter());
    }

    public function getUsuarioController(){
        return new UsuarioController($this->getUsuarioModel(), $this->getPresenter());
    }

    public function getAccesoController(){
        return new AccesoController($this->getUsuarioModel(), $this->getPresenter());
    }

    public function getPerfilController(){
        return new PerfilController($this->getUsuarioModel(), $this->getRankingModel(), $this->getPresenter());
    }

    public function getPartidaController(){
        return new PartidaController($this -> getPartidaModel(),$this->getUsuarioModel(), $this->getPreguntaModel(), $this -> getPresenter());
    }

    public function getRankingController(){
        return new RankingController($this->getRankingModel(),$this->getUsuarioModel(),$this->getPartidaModel(), $this->getPresenter());
    }




    private function getPrincipalModel()
    {
        return new PrincipalModel($this->getDatabase());
    }


    private function getPresenter()
    {
        return new MustachePresenter("./view");
    }


    private function getDatabase()
    {
        $config = parse_ini_file('configuration/config.ini');
        return new Database(
            $config['host'],
            $config['port'],
            $config['user'],
            $config['password'],
            $config["database"]
        );
    }

    public function getRouter()
    {
        $controllerDef = isset($_SESSION['user']) ? "getPrincipalController" : "getAccesoController";
        $methodDef = isset($_SESSION['user']) ? "inicio" : "ingresar";
        return new Router($this, $controllerDef, $methodDef);
    }

    private function getUsuarioModel()
    {
        return new UsuarioModel($this->getDatabase());
    }

    private function getPartidaModel()
    {
        return new PartidaModel($this->getDatabase());
    }

    private function getPreguntaModel()
    {
        return new PreguntaModel($this->getDatabase());
    }

    public function getRankingModel(){
        return new RankingModel($this->getDatabase(), $this->getUsuarioModel());
    }
}
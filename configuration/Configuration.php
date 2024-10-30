<?php
include_once("helper/Database.php"); // esta es la nueva clase para usar stmt
include_once("helper/IncludeFilePresenter.php");
include_once("helper/Router.php");
include_once("helper/MustachePresenter.php");

include_once("controller/HomeController.php");
include_once("model/HomeModel.php");

include_once("controller/AccesoController.php"); //este lo unifique para hacer el register y login en el mismo acceso q usa el model de usuario
include_once("model/UsuarioModel.php");

include_once("controller/PerfilController.php");

include_once ("controller/PartidaController.php");
include_once ("model/PartidaModel.php");

include_once('vendor/mustache/src/Mustache/Autoloader.php');

class Configuration
{
    public function __construct()
    {
    }

    public function getHomeController(){
        return new HomeController($this->getHomeModel(), $this->getPartidaModel(), $this->getUsuarioModel(), $this->getPresenter());
    }

    public function getUsuarioController(){
        return new UsuarioController($this->getUsuarioModel(), $this->getPresenter());
    }

    public function getAccesoController(){
        return new AccesoController($this->getUsuarioModel(), $this->getPresenter());
    }

    public function getPerfilController(){
        return new PerfilController($this->getUsuarioModel(), $this->getPresenter());
    }

    public function getPartidaController(){
        return new PartidaController($this -> getPartidaModel(),$this->getUsuarioModel(), $this -> getPresenter());
    }




    private function getHomeModel()
    {
        return new HomeModel($this->getDatabase());
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
        $controllerDef = isset($_SESSION['user']) ? "getHomeController" : "getAccesoController";
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
}
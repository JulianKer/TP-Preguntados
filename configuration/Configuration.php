<?php
include_once("helper/MysqlDatabase.php");
include_once("helper/MysqlObjectDatabase.php");
include_once("helper/IncludeFilePresenter.php");
include_once("helper/Router.php");
include_once("helper/MustachePresenter.php");

include_once("controller/HomeController.php");
include_once("model/HomeModel.php");

include_once("controller/AccesoController.php"); //este lo unifique para hacer el register y login en el mismo acceso q usa el model de usuario
include_once("model/UsuarioModel.php");

include_once("controller/PerfilController.php");

include_once('vendor/mustache/src/Mustache/Autoloader.php');

class Configuration
{
    public function __construct()
    {
    }

    public function getHomeController(){
        return new HomeController($this->getHomeModel(), $this->getPresenter());
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
        return new MysqlObjectDatabase(
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
}
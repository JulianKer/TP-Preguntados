<?php
include_once("helper/Database.php"); // esta es la nueva clase para usar stmt
include_once("helper/IncludeFilePresenter.php");
include_once("helper/Router.php");
include_once("helper/MustachePresenter.php");
include_once("helper/Seguridad.php");

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

include_once("controller/EditarController.php");

include_once ("controller/DashboardAdminController.php");
include_once ("model/DashboardAdminModel.php");


include_once ("model/CategoriaModel.php");

include_once ("model/ReporteModel.php");

include_once ("helper/Mail.php");
include_once ("helper/SubirImagen.php");
include_once ("helper/QrGenerador.php");
include_once ("helper/pdfGenerador.php");

include_once('vendor/mustache/src/Mustache/Autoloader.php');
include_once('vendor/qrLib/barcode.php');
include_once ('vendor/fpdf/fpdf.php');


class Configuration
{
    public function __construct()
    {
    }

    public function getPrincipalController(){
        return new PrincipalController($this->getPrincipalModel(), $this->getPartidaModel(), $this->getUsuarioModel(), $this->getRankingModel(), $this->getPreguntaModel(), $this->getReporteModel(), $this->getPresenter());
    }

    public function getUsuarioController(){
        return new UsuarioController($this->getUsuarioModel(), $this->getPresenter());
    }

    public function getAccesoController(){
        return new AccesoController($this->getUsuarioModel(), $this->getMail(), $this->getSubirImagen(), $this->getPresenter());
    }

    public function getPerfilController(){
        return new PerfilController($this->getUsuarioModel(), $this->getPartidaModel(), $this->getRankingModel(), $this->getPresenter());
    }

    public function getPartidaController(){
        return new PartidaController($this -> getPartidaModel(),$this->getUsuarioModel(), $this->getPreguntaModel(), $this -> getPresenter());
    }

    public function getRankingController(){
        return new RankingController($this->getRankingModel(),$this->getUsuarioModel(),$this->getPartidaModel(), $this->getPresenter());
    }

    public function getEditarController(){
        return new EditarController($this->getPreguntaModel(), $this->getUsuarioModel(), $this->getCategoriaModel(), $this->getReporteModel(), $this->getPresenter());
    }

public function getDashboardAdminController(){
    return new DashboardAdminController($this->getUsuarioModel(), $this->getPreguntaModel(), $this->getDashboardAdminModel(), $this->getPresenter(), $this->getFpdf());

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
        $seguridad = new Seguridad();
        return new Router($this, $controllerDef, $methodDef, $seguridad);
    }

    private function getUsuarioModel()
    {
        return new UsuarioModel($this->getDatabase());
    }

    private function getPartidaModel()
    {
        return new PartidaModel($this->getDatabase(), $this->getPreguntaModel(), $this->getUsuarioModel());
    }

    private function getPreguntaModel()
    {
        return new PreguntaModel($this->getDatabase());
    }

    public function getRankingModel(){
        return new RankingModel($this->getDatabase(), $this->getUsuarioModel());
    }

    public function getCategoriaModel(){
        return new CategoriaModel($this->getDatabase());
    }

    public function getReporteModel(){
        return new ReporteModel($this->getDatabase());
    }

    public function getDashboardAdminModel(){
        return new DashboardAdminModel ($this->getDatabase());
    }



    public function getMail(){
        return new Mail();
    }

    public function getFpdf(){
        return new pdfGenerador();
    }

    public function getSubirImagen(){
        return new SubirImagen();
    }
}
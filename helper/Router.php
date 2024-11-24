<?php

class Router
{
    private $defaultController;
    private $defaultMethod;
    private $configuration;
    private $seguridad;

    public function __construct($configuration, $defaultController, $defaultMethod, $seguridad){
        $this->defaultController = $defaultController;
        $this->defaultMethod = $defaultMethod;
        $this->configuration = $configuration;
        $this->seguridad = $seguridad;
    }

    public function route($controllerName, $methodName){
        $controller = $this->getControllerFrom($controllerName);
        $this->executeMethodFromController($controller, $methodName);
    }

    private function getControllerFrom($module){
        $controllerName = 'get' . ucfirst($module) . 'Controller';
        $validController = method_exists($this->configuration, $controllerName) ? $controllerName : $this->defaultController;

        // este lo hice por si el controller name q ponen, no coincide con ninguno, entonces te redirijo al principal/inicio
        // PERO neceistaba este if tmb para que NO quede en bucle cuando de verdad querias ir al principal/inicio, entocnes con
        // la segunda condicion, me aseguro de que no se haga el bucle pq digo q sean distintos "getPrincipalController" de
        // "getPrincipalController" por ende NO entra
        if ($validController === $this->defaultController && $controllerName != $this->defaultController) {
            header("location: /principal/inicio");
            exit();
        }
        return call_user_func(array($this->configuration, $validController));
    }

    private function executeMethodFromController($controller, $method){

        $validMethod = method_exists($controller, $method) ? $method : $this->defaultMethod;

        switch ($this->seguridad->verificarPermisosParaEsteMetodo($validMethod)){
            case "AUTORIZADO": // si esta autorizado, tod ok y te dejo entrar
                call_user_func(array($controller, $validMethod));
                break;
            case "DENEGADO": // si no tenes permisos, te mando al default que es el principal/default (default  -> inicio)
                header("location: /principal/" . $this->defaultMethod);
                exit();
            case "NO LOGUEADO": // si no esta logueado, te mando al ingresar directamente
                header("location: /acceso/ingresar");
                exit();
        }


        /*   ANTES EL PROFE NOS DIJO Q ESTO SE PODIA HACER CON EL SWITCH y lo hice pero lo dejo para q vea

        $metodosPublicos = parse_ini_file("permisosUsuarios.ini", true)['metodosPublicos'];

        $validMethod = method_exists($controller, $method) ? $method : $this->defaultMethod;

        if (array_key_exists($validMethod, $metodosPublicos)) {
            call_user_func(array($controller, $validMethod));
        } else {
            if (Seguridad::verificarSiTienePermisos($validMethod)){
                call_user_func(array($controller, $validMethod));
            }else{
                Seguridad::verSiHayUnUsuarioEnLaSesion();
                call_user_func(array($controller, $this->defaultMethod));
            }
        }*/
    }
}
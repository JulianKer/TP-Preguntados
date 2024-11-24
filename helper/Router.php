<?php

class Router
{
    private $defaultController;
    private $defaultMethod;
    private $configuration;

    public function __construct($configuration, $defaultController, $defaultMethod)
    {
        $this->defaultController = $defaultController;
        $this->defaultMethod = $defaultMethod;
        $this->configuration = $configuration;
    }

    public function route($controllerName, $methodName)
    {
        $controller = $this->getControllerFrom($controllerName);
        $this->executeMethodFromController($controller, $methodName);
    }

    private function getControllerFrom($module)
    {
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
        }
    }
}
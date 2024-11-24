<?php

class Seguridad{

    private $metodosPublicos;

    private $metodosSeguros;

    public function __construct(){
        $this->metodosPublicos = parse_ini_file("permisosUsuarios.ini", true)['metodosPublicos'];
        $this->metodosSeguros = parse_ini_file("permisosUsuarios.ini", true)['metodosSeguros'];
    }

    public function verificarPermisosParaEsteMetodo($metodoAValidarPermisos){
        // 1° ver si al q quiere ingresar si es un metodo publico, si es publico, ENTRá nomas
        // 2° si NO es publico, ver si está logueado, si NO esta logueado, te mando al acceso/ingresar
        // 3° si ESTA logueado, veo si tiene permisos, sino te mando al principal/inicio

        if (array_key_exists($metodoAValidarPermisos, $this->metodosPublicos)) {
            return "AUTORIZADO"; // si quiere acceder a un publico, te dejo pq no me interesa si esta logueado ni si tiene permisos pq justamente es PUBLICO
        }

        if (!isset($_SESSION['usuarioObjetoDeLaSesion'])){
            return "NO LOGUEADO";
        }

        $rangoDelUser = $_SESSION['usuarioObjetoDeLaSesion']['rango'];
        if (isset($this->metodosSeguros[$metodoAValidarPermisos])){
            $rangosPermitidosParaEseMetodo = explode(',', $this->metodosSeguros[$metodoAValidarPermisos]); // --> inicio=1,2,3 --> [1, 2, 3]

            if (in_array($rangoDelUser, $rangosPermitidosParaEseMetodo)){
                return "AUTORIZADO";
            }
        }
        return "DENEGADO";
    }





    /*public static function verificarSiTienePermisos($metodoQueQuiereEjecutar){
        $usuarioDeLaSesion = isset($_SESSION['usuarioObjetoDeLaSesion']) ? $_SESSION['usuarioObjetoDeLaSesion'] : null;
        $rangoDelUser = 0;

        if ($usuarioDeLaSesion != null) {
            $rangoDelUser = isset($usuarioDeLaSesion['rango']) ? $usuarioDeLaSesion['rango'] : null;

            $metodos = parse_ini_file('permisosUsuarios.ini', true)['metodosSaguros'];

            if (isset($metodos[$metodoQueQuiereEjecutar])){
                $rangosPermitidosParaEseMetodo = explode(',', $metodos[$metodoQueQuiereEjecutar]); // --> inicio=1,2,3 --> [1, 2, 3]

                if (in_array($rangoDelUser, $rangosPermitidosParaEseMetodo)){
                    return true;
                }
            }
        }
        return false;
    }

    public static function verSiHayUnUsuarioEnLaSesion(){
        if (!isset($_SESSION['usuarioObjetoDeLaSesion'])){
            header("location: /acceso/ingresar");
            exit();
        }
    }*/
}
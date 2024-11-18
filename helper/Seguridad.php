<?php

class Seguridad{

    public static function verificarSiTienePermisos($metodoQueQuiereEjecutar){
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
    }
}
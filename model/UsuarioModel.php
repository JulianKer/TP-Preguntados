<?php

class UsuarioModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function validate($user, $pass)
    {
        $sql = "SELECT 1 
                FROM usuario 
                WHERE nombreusuario = '" . $user. "' 
                AND contrasenia = '" . $pass . "'";

        $usuario = $this->database->query($sql);

        return sizeof($usuario) == 1;
    }

}
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
        $sql = "SELECT 1  FROM usuarios    WHERE username = '" . $user. "'  AND password = '" . $pass . "'";

        $usuario = $this->database->query($sql);

        return sizeof($usuario) == 1;
    }
    public function sinCamposVacios($post){
        foreach ($post as $key => $value) {
            if (empty($value)) {
                return true;
            }
        }
        return false;
    }

    public function buscarEmail($email){
        $sql = "SELECT 1 FROM usuarios WHERE email = '" . $email . "' LIMIT 1";
        $result = $this->database->query($sql);
        if ($result === false) {
            error_log("Error en la consulta SQL: " . $this->database->error);
            return false;
        }
        if (is_object($result)) {
            return $result->num_rows > 0;
        }
        return false;
    }
    public function registrarUsuario($nombre, $nacimiento, $sexo, $email, $password, $username){

        $sql = "insert into usuarios (nombre,nacimiento,sexo,email,password,username) values ('$nombre', '$nacimiento', '$sexo', '$email', '$password', '$username')";
        $registrado =  $this -> database -> execute ($sql);
        if ($registrado === false) {
            error_log("Error en el registro de usuario: " . $this->database->error);
            return false;
        }
        return true;
    }

}
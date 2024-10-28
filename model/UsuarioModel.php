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
                AND contrasenia = '" . $pass . "'
                AND verificado = 1 ";

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
        $sql = "SELECT email FROM usuario WHERE email = '" . $email . "'";
        $result = $this->database->query($sql);

        if ($result === false) {
            return "Hubo un error con la BDD.";
        }
        return $result;
    }
    /*public function registrarUsuario($nombre, $nacimiento, $sexo, $email, $password, $username){
        $sql = "insert into usuarios (nombre,nacimiento,sexo,email,password,username) values ('$nombre', '$nacimiento', '$sexo', '$email', '$password', '$username')";
        $registrado =  $this -> database -> execute ($sql);
        if ($registrado === false) {
            error_log("Error en el registro de usuario: " . $this->database->error);
            return false;
        }
        return true;
    }*/

    public function registrarUsuario($nombre,$apellido,$nacimiento,$sexo, $ubicacion, $email, $password, $username, $profile_pic){
        $fechaRegistro = date('Y-m-d'); // esta seria la fecha de hoy, la pongo aca pq la uso solo para cuando se registra directamente
         $sql = "INSERT INTO `usuario` (`nombre`, `apellido`, `nombreusuario`, `contrasenia`, `email`, `añonacimiento`, `ubicacion`, `fecharegistro`, `fotoperfil`, `sexo`) VALUES ('" . $nombre . "', '" . $apellido . "', '" . $username . "', '" . $password . "', '" . $email . "', '" . $nacimiento . "', '" . $ubicacion . "', '" . $fechaRegistro . "', '" . $profile_pic . "', '" . $sexo . "')";
         return $this->database->insertar($sql);
    }

    public function obtenerIdUserPorUserName($username){
        $sql = "SELECT id FROM usuario WHERE nombreusuario = '" . $username . "'";
        return $this->database->queryAssoc($sql);
    }

    public function obtenerUsuarioPorId($id){
        $sql = "SELECT * FROM usuario WHERE id = '" . $id . "'";
        return $this->database->query($sql);
    }
    public function verificarEmail($usuario_id) {
        $sql = "UPDATE usuario SET verificado = 1 WHERE id = " . intval($usuario_id);
        $resultado = $this->database->execute($sql);
        return $resultado === 1;
        }
        public function getLastInsert(){
            return $this ->database -> getLastInsert();
        }
}
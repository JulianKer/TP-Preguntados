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
        return $this->database->validate($user, $pass);
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
        return $this->database->buscarEmail($email);
    }

    public function registrarUsuario($nombre,$apellido,$nacimiento,$sexo, $ubicacion, $email, $password, $username, $profile_pic){
        return $this->database->registrarUsuario($nombre,$apellido,$nacimiento,$sexo, $ubicacion, $email, $password, $username, $profile_pic);
    }

    public function obtenerIdUserPorUserName($username){
        return $this->database->obtenerIdUserPorUserName($username);
    }

    public function obtenerUsuarioPorId($id){
        return $this->database->obtenerUsuarioPorId($id);
    }
    public function verificarEmail($usuario_id) {
        return $this->database->verificarEmail($usuario_id);
    }
    public function getLastInsert(){
        return $this ->database -> getLastInsert();
    }

    public function obtenerCoordenadas($ubicacion){
        list($latitud, $longitud) = explode(", ", $ubicacion);
        $coordenadas["lng"] = $longitud;
        $coordenadas["lat"] = $latitud;
        return $coordenadas;
    }

    public function setearMusicaActivadaDelUsuario($activacionDeMusica, $idUsuario){
        $this->database->setearMusicaActivadaDelUsuario($activacionDeMusica, $idUsuario);
    }


    public function obtenerTodosLosUsuarios(){
        return $this->database->obtenerTodosLosUsuarios();
    }

    public function actualizarPuntaje($puntaje, $idUsuario){
        $this->database->actualizarPuntaje($puntaje, $idUsuario);
    }
}
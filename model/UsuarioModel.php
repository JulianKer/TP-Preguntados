<?php
//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;
//use PHPMailer\PHPMailer\SMTP;

require ("vendor/phpMailer/src/PHPMailer.php");
require ("vendor/phpMailer/src/Exception.php");
require ("vendor/phpMailer/src/SMTP.php");

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

    public function obtenerUltimoIdInsertadoMasUno(){
        return $this->database->obtenerUltimoIdInsertadoDeTablaUsuario()['id']+1;
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

//    public function enviarMail($idUsuario){
//        $mail = new PHPMailer(true);
//        // Encontrar usuario aca
//        $usuarioEncontrado = $this->obtenerUsuarioPorId($idUsuario)[0];
//
//
//        try {
//            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
//
//            $mail->isSMTP();
//            $mail->SMTPDebug = 0;
//            $mail->SMTPAuth = true;
//            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
//            $mail->Host = 'smtp.gmail.com';
//            $mail->Port = 465;
//
//            $mail->Username = 'quizmasterPW2@gmail.com';
//            $mail->Password = 'othi zrtl rksv cbyy';
//            $mail->setFrom('quizmasterPW2@gmail.com', 'QuizMaster');
//
//            //echo json_encode($usuarioEncontrado);
//            $mail->addAddress($usuarioEncontrado["email"], $usuarioEncontrado["nombre"]);
//            $mail->isHTML(true);
//            $mail->Subject = 'Confirmacion de Mail';
//            $mail->Body =
//                '   <h1>Bienvenido a QuizMaster, por favor, cliquea el siguiente link para poder confirmar tu cuenta:</h1>
//                    <form action="http://localhost/acceso/verificarEmail" method="post" enctype="multipart/form-data">
//                        <input type="hidden" name="id_usuario" value="'.$usuarioEncontrado["id"].'">
//                        <button type="submit">Validar Registro</button>
//                    </form>';
//
//            $mail->send();
//            //echo 'Se envio el mail';
//        } catch (Exception $e) {
//            echo "El mail no pudo ser enviado {$mail->ErrorInfo}";
//        }
//    }

    public function obtenerTodosLosUsuarios(){
        return $this->database->obtenerTodosLosUsuarios();
    }

    public function actualizarPuntaje($puntaje, $idUsuario){
        $this->database->actualizarPuntaje($puntaje, $idUsuario);
    }

    public function actualizarUsuario($usuario){
        $this->database->actualizarUsuario($usuario);
    }

    public function saberSiEsJugador($rango){
        return $rango === 3;
    }

    public function saberSiEsAdmin($rango){
        return $rango === 1;
    }

    public function saberSiEsEditor($rango){
        return $rango === 2;
    }

    public function obtenerCantidadDeJugadores(){
       return $this -> database -> obtenerCantidadDeJugadores();
    }

    public function obtenerCantidadDeJugadoresPorSexo(){
        return $this -> database -> obtenerCantidadDeJugadoresPorSexo();
    }





}
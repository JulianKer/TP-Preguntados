<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class Mail{
    public function enviarMail($usuarioEncontrado){
        $mail = new PHPMailer(true);
        // Encontrar usuario aca
        //$usuarioEncontrado = $this->obtenerUsuarioPorId($idUsuario)[0];


        try {
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;

            $mail->isSMTP();
            $mail->SMTPDebug = 0;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 465;

            $mail->Username = 'quizmasterPW2@gmail.com';
            $mail->Password = 'othi zrtl rksv cbyy';
            $mail->setFrom('quizmasterPW2@gmail.com', 'QuizMaster');

            //echo json_encode($usuarioEncontrado);
            $mail->addAddress($usuarioEncontrado["email"], $usuarioEncontrado["nombre"]);
            $mail->isHTML(true);
            $mail->Subject = 'Confirmacion de Mail';
            $mail->Body =
                '   <h1>Bienvenido a QuizMaster, por favor, cliquea el siguiente link para poder confirmar tu cuenta:</h1>
                    <form action="http://localhost/acceso/verificarEmail" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id_usuario" value="'.$usuarioEncontrado["id"].'">
                        <button type="submit">Validar Registro</button>
                    </form>';

            $mail->send();
            //echo 'Se envio el mail';
        } catch (Exception $e) {
            echo "El mail no pudo ser enviado {$mail->ErrorInfo}";
        }
    }

}
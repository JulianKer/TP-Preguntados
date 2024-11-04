<?php

class AccesoController
{
    private $presenter;
    private $model;
    private $mail;

    public function __construct($model, $mail, $presenter) // ver pq deberia recibir tmb el model ya q necesito logica je
    {
        $this->presenter = $presenter;
        $this->model = $model;
        $this->mail = $mail;
    }

    public function ingresar(){
        $data = [];
        if (isset($_GET["msj"])){
            $data["msj"] = $_GET["msj"];
        }
        if (isset($_GET["exito"])){
            $data["exito"] = $_GET["exito"];
        }
        $this->presenter->show("login", $data);
    }

    public function registrar(){
        $data = [];
        if (isset($_GET["msj"])){
            $data["msj"] = $_GET["msj"];
        }
        $this->presenter->show("register", $data);
    }

    public function validarIngreso(){
        $user = $_POST['username'];
        $pass = $_POST['password'];

        $validation = $this->model->validate($user, $pass);

        if ($validation) {
            $_SESSION['user'] = $user;
;
            $_SESSION['idUser'] = $this->model->obtenerIdUserPorUserName($user)["id"];
            header('location: /principal/inicio');
            exit();
        }
        header('location: /acceso/ingresar?msj=' . urldecode("Usuario no verificado o sus credenciales no coinciden."));
        exit();
    }

    public function cerrarSesion(){
        session_unset();
        session_destroy();
        header("location: /");
        exit();
    }

    public function nuevoUsuario() {
        $data = [];

        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $nacimiento = $_POST['nacimiento'];
        $sexo = $_POST['sexo'];
        $ubicacion = $_POST['ubicacion'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $username = $_POST['username'];
        $profile_pic = $_POST['profile_pic']; // aca ver lo de la img pq tenemos q recibirlo por $_FILE, pero bueno, ese post te da el nombre del archivo + extension

        $vacios = $this->model->sinCamposVacios($_POST);

        if ($vacios){
            $msj = "Los campos no pueden estar vacíos.";
            header("location: /acceso/registrar?msj=" . urldecode($msj));
            exit();
        }

        if ($password != $confirm_password){
            $msj = "Las contraseñas no coinciden.";
            header("location: /acceso/registrar?msj=" . urldecode($msj));
            exit();
        }

        $emailEncontrado = $this->model->buscarEmail($email);
        if ($emailEncontrado == "Hubo un error con la BDD."){
            $msj = $emailEncontrado;
            header("location: /acceso/registrar?msj=" . urldecode($msj));
            exit();
        }

        echo $emailEncontrado;
        if (!empty($emailEncontrado)){
            $msj = "El usuario con ese mail ya está registrado.";
            header("location: /acceso/registrar?msj=" . urldecode($msj));
            exit();
        }

        //echo $profile_pic;
        if(!$this->model->registrarUsuario($nombre,$apellido,$nacimiento,$sexo, $ubicacion, $email, $password, $username, $profile_pic)){
            $msj = "No se pudo registrar.";
            header("location: /acceso/registrar?msj=" . urldecode($msj));
            exit();
        }



        // aca ahora faltaria guardar la img del perfil ya que YA se registro correctamente si llego hasta aca




//      $data ["usuario_id"] = $this->model->getLastInsert("usuario");
        $idUsuario = $this->model->obtenerIdUserPorUserName($username)["id"];
        $data ["usuario_id"] = $idUsuario;
        $data ["registroExitoso"] = "Usuario registrado con éxito. Para terminar verifique su email.";
        $userRecienRegistrado = $this->model->obtenerUsuarioPorId($idUsuario)[0];
        $this->mail->enviarMail($userRecienRegistrado);

        $this->presenter->show("verificarEmail", $data);
    }

    public function verificarEmail(){
        $data = [];
        if (isset($_POST['id_usuario'])) {
            $usuario_id = $_POST['id_usuario'];
        }

    $resultado = $this -> model -> verificarEmail($usuario_id);

            if($resultado){
            $data["exito"] = "Su email ha sido verificado exitosamente";
            $this -> presenter -> show ("login", $data);
            }
            else{
                $data ["verifacionNoExitosa"] = "ERRORR";
            }
    }
}
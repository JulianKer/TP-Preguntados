<?php

class AccesoController
{
    private $presenter;
    private $model;
    private $mail;
    private $subirImg;

    public function __construct($model, $mail, $subirImg, $presenter) // ver pq deberia recibir tmb el model ya q necesito logica je
    {
        $this->presenter = $presenter;
        $this->model = $model;
        $this->mail = $mail;
        $this->subirImg = $subirImg;
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
        //$profile_pic = $_POST['profile_pic']; // este ya no lo uso pq abajo ya reemplazo el nombre del archivo por como se guardo con el id del user

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

        // con este subo la img del user que tenga d nombre el id del user para q no hayan lios
        $siguienteId = $this->model->obtenerUltimoIdInsertadoMasUno();
        $msjsDevueltosPorSubirImg = $this->subirImg->subir($_FILES['profile_pic'], $siguienteId);

        if (isset($msjsDevueltosPorSubirImg['error'])){
            header("location: /acceso/registrar?msj=" . urldecode($msjsDevueltosPorSubirImg['error']));
            exit();
        }

        $nombreDeLaImgGuardadaConExtencion = $msjsDevueltosPorSubirImg['nombreDelArchivoGuardado'];
        if(!$this->model->registrarUsuario($nombre,$apellido,$nacimiento,$sexo, $ubicacion, $email, $password, $username, $nombreDeLaImgGuardadaConExtencion)){
            $msj = "No se pudo registrar.";
            header("location: /acceso/registrar?msj=" . urldecode($msj));
            exit();
        }
        $idUsuario = $this->model->obtenerIdUserPorUserName($username)["id"];
        $userRecienRegistrado = $this->model->obtenerUsuarioPorId($idUsuario)[0];
        $this->mail->enviarMail($userRecienRegistrado);

        $data ["usuario_id"] = $idUsuario;
        $data ["registroExitoso"] = "Usuario registrado con éxito. Para terminar verifique su email.";
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
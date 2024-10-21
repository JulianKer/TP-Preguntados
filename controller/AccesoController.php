<?php

class AccesoController
{
    private $presenter;
    private $model;

    public function __construct($model, $presenter) // ver pq deberia recibir tmb el model ya q necesito logica je
    {
        $this->presenter = $presenter;
        $this->model = $model;
    }

    public function ingresar(){
        $this->presenter->show("login");
    }

    public function registrar(){
        $this->presenter->show("register");
    }

    public function validarIngreso(){
        $user = $_POST['username'];
        $pass = $_POST['password'];

        $validation = $this->model->validate($user, $pass);

        if ($validation) {
            $_SESSION['user'] = $user;
            header('location: /pokedex/list');
            exit();
        }
        header('location: /acceso/ingresar?msj=' . urldecode("Usuario o contraseña incorrecta."));
        exit();
    }
     /*   public function nuevoUsuario(){
        $data["errores"] = [];
        $nombre = $_POST ['nombre'];
        $nacimiento = $_POST ['nacimiento'];
        $sexo = $_POST ['sexo'];
        $email = $_POST ['email'];
        $password = $_POST ['password'];
        $confirm_password = $_POST ['confirm_password'];
        $username = $_POST ['username'];
        $profile_pic = $_POST ['profile_pic'];

        $vacios = $this -> model -> sinCamposVacios($_POST);

        if($vacios){

            array_push($data,["valores_incompletos",$_POST]);
            array_push($data ["errores"],"Algunos de los campos se encuentra incompleto");
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $data[$key] = json_encode($value); // Convierte el array a un string
                }
            }
            $this -> presenter ->show ('register', $data);
        }

        $emailEncontrado = $this->model->buscarEmail($_POST["email"]);

        if ($emailEncontrado) {
            array_push($data["errores"], "Este email ya se encuentra registrado");

            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $data[$key] = json_encode($value); // Convierte el array a un string
                }
            }

            // Mostrar el error y detener la ejecución para no registrar al usuario
            $this->presenter->show('register', $data);
            return; // Detiene la ejecución para evitar que continúe el registro
        }

// Código para registrar al usuario si no se encontró el email
        $registrado = $this->model->registrarUsuario($nombre, $nacimiento, $sexo, $email, $password, $username);

        if ($registrado) {
            header("Location: /login");
            exit();
        }

    } */
    public function nuevoUsuario() {
        $data["errores"] = [];
        $nombre = $_POST['nombre'];
        $nacimiento = $_POST['nacimiento'];
        $sexo = $_POST['sexo'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $username = $_POST['username'];
        $profile_pic = $_POST['profile_pic'];

        $vacios = $this->model->sinCamposVacios($_POST);

        if($vacios){

            array_push($data,["valores_incompletos",$_POST]);
            array_push($data ["errores"],"Algunos de los campos se encuentra incompleto");
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $data[$key] = json_encode($value); // Convierte el array a un string
                }
            }
            $this -> presenter ->show ('register', $data);
        }

        $emailEncontrado = $this->model->buscarEmail($email);

        if ($emailEncontrado) {
            array_push($data["errores"], "Este email ya se encuentra registrado");
            $this->presenter->show('register', $data);
            return;
        }

        $registrado = $this->model->registrarUsuario($nombre, $nacimiento, $sexo, $email, $password, $username);

        if ($registrado) {
            header("Location: /login");
            exit();
        } else {
            array_push($data["errores"], "Error en el registro del usuario.");
            $this->presenter->show('register', $data);
        }
    }

}
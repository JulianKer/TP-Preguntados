<?php

class PrincipalController
{
    private $model;
    private $modelPartida;
    private $presenter;
    private $modelUsuario;
    private $modelRanking;
    private $modelPreguntas;
    private $modelReporte;
    public function __construct($model, $modelPartida, $modelUsuario, $modelRanking, $modelPreguntas, $modelReporte, $presenter)
    {
        $this->model = $model;
        $this->modelPartida = $modelPartida;
        $this->modelUsuario = $modelUsuario;
        $this->modelRanking = $modelRanking;
        $this->modelPreguntas = $modelPreguntas;
        $this->modelReporte = $modelReporte;
        $this->presenter = $presenter;
    }

    public function inicio(){
        //Seguridad::verSiHayUnUsuarioEnLaSesion();

        $idUsuario = $_SESSION['idUser'];
        $userName = $_SESSION['user']; // es el user name pero en el sesion esta como user
        $_SESSION["errorCrear"] = null;
        $_SESSION["exitoCrear"] = null;
        $userEncontrado = $this->modelUsuario->obtenerUsuarioPorId($idUsuario)[0];
        $rangoDelUsuario = $userEncontrado["rango"];

        $data["musicaActivada"] = $userEncontrado["musica"];
        $data["objUsuario"] = $userEncontrado;
        $data['user'] = $userName;
        $data['idUsuario'] = $idUsuario;

        switch ($rangoDelUsuario) {
            case 1: // admin
                $data["esAdmin"] = $this->modelUsuario->saberSiEsAdmin($userEncontrado["rango"]);

                break;
                case 2: // editor
                    unset($_SESSION["idReporte"]);

                    $data["esEditor"] = $this->modelUsuario->saberSiEsEditor($userEncontrado["rango"]);

                    $data["preguntas"] = $this->modelPreguntas->obtenerTodasLasPreguntasDeLaTablaAprobadasYDesactivadas();
                    $data["total"] = count($data["preguntas"]);

                    $desactivadas = $this->modelPreguntas->obtenerDesactivadas();
                    $habilitadas = $this->modelPreguntas->obtenerHabilitadas();
                    $data["totalDesactivadas"] = $desactivadas ? count($desactivadas) : 0;
                    $data["totalHabilitadas"] = $habilitadas ? count($habilitadas) : 0;

                    $data["exitoMsjSobreDesactivacion"] = isset($_SESSION["exitoMsjSobreDesactivacion"]) ? $_SESSION["exitoMsjSobreDesactivacion"] : false;
                    $data["errorMsjSobreDesactivacion"] = isset($_SESSION["errorMsjSobreDesactivacion"]) ? $_SESSION["errorMsjSobreDesactivacion"] : false;

                    $data["exitoMsjSobreHabilitacion"] = isset($_SESSION["exitoMsjSobreHabilitacion"]) ? $_SESSION["exitoMsjSobreHabilitacion"] : false;
                    $data["errorMsjSobreHabilitacion"] = isset($_SESSION["errorMsjSobreHabilitacion"]) ? $_SESSION["errorMsjSobreHabilitacion"] : false;

                    $data["errorEditar"] = isset($_SESSION["errorEditar"]) ? $_SESSION["errorEditar"] : false;
                    $data["exitoEditar"] = isset($_SESSION["exitoEditar"]) ? $_SESSION["exitoEditar"] : false;

                    unset($_SESSION["exitoMsjSobreHabilitacion"]);
                    unset($_SESSION["errorMsjSobreHabilitacion"]);
                    unset($_SESSION["exitoMsjSobreDesactivacion"]);
                    unset($_SESSION["errorMsjSobreDesactivacion"]);
                    unset($_SESSION["errorEditar"]);
                    unset($_SESSION["exitoEditar"]);

                    break;
                    case 3: // jugador
                        $data["esJugador"] = $this->modelUsuario->saberSiEsJugador($userEncontrado["rango"]);

                        $data["partidaPendiente"] = (bool)$this->modelPartida->buscarSiHayUnaPartidaEnCursoParaEsteUser($idUsuario);
                        $data["posicionEnElRanking"] = $this->modelRanking->dameLaPosicionEnElRankingDeEsteUsuario($userEncontrado["id"]);
                        $data["partidasDelUsuario"] = $this->modelPartida->obtenerPartidasDelUsuario($userEncontrado["id"]);
                        break;
        }
        $this->presenter->show('home', $data);
    }

    public function redirectHome(){
        header('location: /principal/inicio');
        exit();
    }
    /*-----------------------------------------------------------------------------------*/

    /*---------------------------------metodos editor----------------------------------*/
    public function desactivar(){
        $idDePreguntaADesactivar = isset($_GET['var1']) ? $_GET['var1'] : 0;

        $msj = $this->modelPreguntas->desactivarPregunta($idDePreguntaADesactivar);

        if ($msj === "Pregunta " . $idDePreguntaADesactivar . " desactivada correctamente."){
            $_SESSION['exitoMsjSobreDesactivacion'] = $msj;
        }else{
            $_SESSION['errorMsjSobreDesactivacion'] = $msj;
        }
        unset($_SESSION["exitoMsjSobreHabilitacion"]);
        unset($_SESSION["errorMsjSobreHabilitacion"]);

        $this->redirectHome();
    }

    public function habilitar(){
        $idDePreguntaADesactivar = isset($_GET['var1']) ? $_GET['var1'] : 0;

        $msj = $this->modelPreguntas->habilitarPregunta($idDePreguntaADesactivar);

        if ($msj === "Pregunta " . $idDePreguntaADesactivar . " habilitada correctamente."){
            $_SESSION['exitoMsjSobreHabilitacion'] = $msj;
        }else{
            $_SESSION['errorMsjSobreHabilitacion'] = $msj;
        }
        unset($_SESSION["exitoMsjSobreDesactivacion"]);
        unset($_SESSION["errorMsjSobreDesactivacion"]);

        $this->redirectHome();
    }


    public function reportes(){
        $idUsuario = $_SESSION['idUser'];
        $userName = $_SESSION['user']; // es el user name pero en el sesion esta como user
        $_SESSION["errorCrear"] = null;
        $_SESSION["exitoCrear"] = null;
        $userEncontrado = $this->modelUsuario->obtenerUsuarioPorId($idUsuario)[0];
        $rangoDelUsuario = $userEncontrado["rango"];

        $data["musicaActivada"] = $userEncontrado["musica"];
        $data["objUsuario"] = $userEncontrado;
        $data['user'] = $userName;
        $data['idUsuario'] = $idUsuario;

        $data["exitoMsjSobreEliminacionReporte"] = isset($_SESSION["exitoMsjSobreEliminacionReporte"]) ? $_SESSION["exitoMsjSobreEliminacionReporte"] : false;
        $data["errorMsjSobreEliminacionReporte"] = isset($_SESSION["errorMsjSobreEliminacionReporte"]) ? $_SESSION["errorMsjSobreEliminacionReporte"] : false;

        $data["exitoMsjSobreAprobacionReporte"] = isset($_SESSION["exitoMsjSobreAprobacionReporte"]) ? $_SESSION["exitoMsjSobreAprobacionReporte"] : false;
        $data["errorMsjSobreAprobacionReporte"] = isset($_SESSION["errorMsjSobreAprobacionReporte"]) ? $_SESSION["errorMsjSobreAprobacionReporte"] : false;

        unset($_SESSION["exitoMsjSobreEliminacionReporte"]);
        unset($_SESSION["errorMsjSobreEliminacionReporte"]);
        unset($_SESSION["exitoMsjSobreAprobacionReporte"]);
        unset($_SESSION["errorMsjSobreDesactivacion"]);

        $data['reportes'] = $this->modelReporte->obtenerTodosLosReportes();
        $data['total'] = count($data['reportes']);
        $this->presenter->show('reportes', $data);
    }

    public function eliminarReporte(){
        $idReporte = isset($_GET['var1']) ? $_GET['var1'] : 0;

        $reporteEncontrado = $this->modelReporte->obtenerReportePorId($idReporte);

        if ($reporteEncontrado){
            $msj = $this->modelReporte->eliminarReporte($idReporte);

            if ($msj === "Reporte " . $idReporte . " eliminado correctamente."){
                $idEstadoACambiar = 4; // (aprobada), pq el reporte "estaria mal" entonces al eliminarlo, dejo la pregunta aprobada como si no hubiese pasado nada
                $this->modelPreguntas->cambiarEstadoDePregunta($reporteEncontrado['id_pregunta'], $idEstadoACambiar);
                $_SESSION['exitoMsjSobreEliminacionReporte'] = $msj;
            }else{
                $_SESSION['errorMsjSobreEliminacionReporte'] = $msj;
            }
        }else{
            $_SESSION['errorMsjSobreEliminacionReporte'] = "El reporte " . $idReporte . " no existe ";
        }

        unset($_SESSION["exitoMsjSobreAprobacionReporte"]);
        unset($_SESSION["errorMsjSobreAprobacionReporte"]);

        header("location: /principal/reportes");
        exit();
    }


    public function aprobarReporte(){
        $idReporte = isset($_GET['var1']) ? $_GET['var1'] : 0;

        $reporteEncontrado = $this->modelReporte->obtenerReportePorId($idReporte);

        if ($reporteEncontrado){
            $_SESSION['idReporte'] = $idReporte;
            header("location: /editar/pregunta/" . $reporteEncontrado['id_pregunta']);
            exit();
        }else{
            $_SESSION['errorMsjSobreEliminacionReporte'] = "El reporte " . $idReporte . " no existe ";
        }
        unset($_SESSION["exitoMsjSobreEliminacionReporte"]);

        header("location: /principal/reportes");
        exit();
    }
    /*-----------------------------------------------------------------------------------*/


    public function habilitarTodasLasPreguntasDesactivadas(){
        $this->modelPreguntas->habilitarTodasLasPreguntasDesactivadas();
        $this->redirectHome();
    }

    public function desactivarTodasLasPreguntasHabilitadasYReportadas(){
        $this->modelPreguntas->desactivarTodasLasPreguntasHabilitadasYReportadas();
        $this->redirectHome();
    }
}
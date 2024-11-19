<?php

class ReporteModel{

    private $database;
    public function __construct($database){
        $this->database = $database;
    }

    public function obtenerTodosLosReportes(){
        return $this->database->obtenerTodosLosReportes();
    }

    public function eliminarReporte($idReporte){
        /*aca antes de eliminarlo tengo que cambiarEstadoPregunta a aprobada (4) ver q ya lo tengo el metodo ese, solo tengo q implemetnar el modelPregunta en este modelReporte y llamarlo
        --------------------
        -------------------
        -----------------
        -----------------
        ----------------
        ---------------- PONOG ESTO PARA QUE JULIAN SE DE CUENTA QUE TIENE Q HACER ACA*/
        $reporteEncontrado = $this->obtenerReportePorId($idReporte);

        if ($reporteEncontrado){
            $this->database->eliminarReporte($idReporte);
            return "Reporte " . $idReporte . " eliminado correctamente.";
        }else{
            return "No se pudo eliminar el reporte " . $idReporte . ".";
        }
    }

    public function obtenerReportePorId($idReporte){
        return $this->database->obtenerReportePorId($idReporte);
    }
}
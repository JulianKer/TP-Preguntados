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
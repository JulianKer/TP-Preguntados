<?php

class SubirImagen{
    private $carpetaDestino;
    private $tiposPermitidos;
    private $tamanioMaximo;

    public function __construct($carpetaDestino = '../public/images/profiles/', $tiposPermitidos = ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'], $tamanioMaximo = 5000000) {
        $this->carpetaDestino = __DIR__ . '/' . $carpetaDestino;
        $this->tiposPermitidos = $tiposPermitidos;
        $this->tamanioMaximo = $tamanioMaximo;

        if (!is_dir($this->carpetaDestino)) {
            mkdir($this->carpetaDestino, 0755, true); // lo pongo pero no creo q se deba crear el destino pq ya se que existen, pero por las dudas, q se cree sino es asi
        }
    }

    public function subir($archivo, $nombreNuevoDelArchivo) {
        if (!file_exists($archivo['tmp_name'])) {
            return ["error" => "El archivo temporal no existe. Verifica la subida."];
        }

        if ($archivo['error'] > 0) {
            return ["error" => "Error al subir la imagen. Código de error: " . $archivo['error']];
        }

        if (!in_array($archivo['type'], $this->tiposPermitidos)) {
            return ["error" => "Tipo de archivo no permitido. Solo se permiten JPEG, JPG, PNG, WEBP Y SVG."];
        }

        if ($archivo['size'] > $this->tamanioMaximo) {
            return ["error" => "El archivo es demasiado grande. Tamaño máximo permitido: " . ($this->tamanioMaximo / 1000000) . " MB"];
        }

        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        $nombreFinalConExtencion = $nombreNuevoDelArchivo . '.' . $extension;

        if (!is_dir($this->carpetaDestino) || !is_writable($this->carpetaDestino)) {
            return ["error" => "La carpeta de destino no existe o no tiene permisos de escritura."];
        }

        $rutaDestino = $this->carpetaDestino . $nombreFinalConExtencion;

        if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
            return ["nombreDelArchivoGuardado" => $nombreFinalConExtencion, "rutaDestino" => $rutaDestino]; //VER ANTES DE MOVERLA SI YA EXISTE EL ARCHIVO CON ESE NOMBRE PQ PROBÉ Y SE SOBREESCRIBE, PUEDE Q SIEMPRE ME DE EL MISMO ID EL DE ULITMO INSERT, CAPAZ PROBAR GUARDARLA CON NOMBREYAPAELLIDO O ALGO DE ESO NOSE, LA PSIA A LA ANTERIOR PERO ANDAR ANDA, SE SUBE BIEN
        } else {
            return ["error" => "Error al mover el archivo a la carpeta de destino. Verifica permisos y ruta: " . $rutaDestino];
        }
    }
}
<?php

class QrGenerador{

    public static function generarYguardarQr($contenidoDelQr){
        $generator = new barcode_generator();

        $svg = $generator->render_svg("qr", $contenidoDelQr, "");
        $filePath = $_SERVER['DOCUMENT_ROOT'] . "/public/images/qr/qr.svg";
        file_put_contents($filePath, $svg);
        //echo $svg;
    }
}
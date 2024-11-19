<?php
class Database{

    private $conn;
    private $error = "";

    public function __construct($host, $port, $username, $password, $database){
        try{
            $this->conn = new mysqli($host, $username, $password, $database, $port);
        }catch (Exception $e){
            $this->error = "Falló la conexión a la base de datos.";
        }
    }

    public function queryConFetchAll($sql){
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    /* --------------------- INICIO user------------------------------------------------------------------*/
        public function validate($user, $pass)
        {
            /*$sql = "SELECT 1
                    FROM usuario
                    WHERE nombreusuario = '" . $user. "'
                    AND contrasenia = '" . $pass . "'
                    AND verificado = 1 ";

            $usuario = $this->database->query($sql);

            return sizeof($usuario) == 1;*/

        $stmt = $this->conn->prepare("SELECT 1 
                                       FROM usuario 
                                       WHERE nombreusuario = ? 
                                       AND contrasenia = ? 
                                       AND verificado = 1");
        $stmt->bind_param("ss", $user, $pass);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->num_rows === 1;
    }

    public function obtenerUltimoIdInsertadoDeTablaUsuario(){
        $stmt = $this->conn->prepare("SELECT id FROM usuario ORDER BY id DESC LIMIT 1");
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function buscarEmail($email){
        /*$sql = "SELECT email FROM usuario WHERE email = $email";
        $result = $this->database->query($sql);

        if ($result === false) {
            return "Hubo un error con la BDD.";
        }
        return $result;*/

        $stmt = $this->conn->prepare("SELECT email FROM usuario WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result === false) {
            return "Hubo un error con la BDD.";
        }
        return $result->fetch_assoc();
    }

    public function registrarUsuario($nombre,$apellido,$nacimiento,$sexo, $ubicacion, $email, $password, $username, $profile_pic){
        /*$fechaRegistro = date('Y-m-d'); // esta seria la fecha de hoy, la pongo aca pq la uso solo para cuando se registra directamente
        $sql = "INSERT INTO `usuario` (`nombre`, `apellido`, `nombreusuario`, `contrasenia`, `email`, `añonacimiento`, `ubicacion`, `fecharegistro`, `fotoperfil`, `sexo`) VALUES ('" . $nombre . "', '" . $apellido . "', '" . $username . "', '" . $password . "', '" . $email . "', '" . $nacimiento . "', '" . $ubicacion . "', '" . $fechaRegistro . "', '" . $profile_pic . "', '" . $sexo . "')";
        return $this->database->insertar($sql);*/

        $fechaRegistro = date('Y-m-d');
        $verificado = 0;
        $musica = 1;
        $stmt = $this->conn->prepare("INSERT INTO `usuario` (`nombre`, `apellido`, `nombreusuario`, `contrasenia`, `email`, `añonacimiento`, `ubicacion`, `fecharegistro`, `fotoperfil`, `sexo`, `verificado`, `musica`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssii", $nombre, $apellido, $username, $password, $email, $nacimiento, $ubicacion, $fechaRegistro, $profile_pic, $sexo, $verificado, $musica);
        if ($stmt->execute()) {
            return $stmt->insert_id;
        } else {
            return false;
        }
    }

    public function obtenerIdUserPorUserName($username){
        /*$sql = "SELECT id FROM usuario WHERE nombreusuario = '" . $username . "'";
        return $this->database->queryAssoc($sql);*/

        $stmt = $this->conn->prepare("SELECT id FROM usuario WHERE nombreusuario = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function obtenerUsuarioPorId($id){
        /*$sql = "SELECT * FROM usuario WHERE id = '" . $id . "'";
        return $this->database->query($sql);*/

        $stmt = $this->conn->prepare("SELECT * FROM usuario WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    public function verificarEmail($usuario_id) {
        /*$sql = "UPDATE usuario SET verificado = 1 WHERE id = " . intval($usuario_id);
        $resultado = $this->database->execute($sql);
        return $resultado === 1;*/

        $stmt = $this->conn->prepare("UPDATE usuario SET verificado = 1 WHERE id = ?");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        return $stmt->affected_rows === 1;
    }
    public function getLastInsert(){
        return $this->conn->insert_id;
    }

    public function setearMusicaActivadaDelUsuario($activacionDeMusica, $idUsuario){
        /*$sql = "UPDATE usuario SET musica = $activacionDeMusica WHERE id = $idUsuario";
        $this->database->execute($sql);*/

        $stmt = $this->conn->prepare("UPDATE usuario SET musica = ? WHERE id = ?");
        $stmt->bind_param("ii", $activacionDeMusica, $idUsuario);
        $stmt->execute();
    }

    public function actualizarPuntaje($puntaje, $idUsuario){
        $stmt = $this->conn->prepare("UPDATE usuario SET puntaje_usuario = puntaje_usuario + ? WHERE id = ?");
        $stmt->bind_param("ii", $puntaje, $idUsuario);
        $stmt->execute();
    }
    /* --------------------- FIN user------------------------------------------------------------------*/



    /* --------------------- INICIO PARTIDAS------------------------------------------------------------------*/
        public  function crearPartidaEnCursoParaEsteUser($idUser){
    //        $sql = "INSERT INTO partida (id_usuario, puntaje, terminada) VALUES ( $idUser, 0, FALSE);";
    //        return $this->conn->insertar($sql);

            $stmt = $this->conn->prepare("INSERT INTO partida (id_usuario, puntaje, terminada) VALUES (?, 0, FALSE)");
            $stmt->bind_param("i", $idUser);

            if ($stmt->execute()) {
                return $this->conn->insert_id;
            } else {
                return false;
            }

        }

        public function buscarSiHayUnaPartidaEnCursoParaEsteUser($idUser){
            //$sql = "SELECT * FROM partida WHERE id_usuario = $idUser AND terminada = 0";
            //return $this->conn->queryAssoc($sql);

            $stmt = $this->conn->prepare("SELECT * FROM partida WHERE id_usuario = ? AND terminada = 0");
            $stmt->bind_param("i", $idUser);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }

        public function buscarUltimaPreguntaNoResponididaDeLaPartida($idPartida){
            //$sql = "SELECT * FROM preguntapartida WHERE id_partida = $idPartida AND respondida = false LIMIT 1";
            //return $this->conn->queryAssoc($sql);

            $stmt = $this->conn->prepare("SELECT * FROM preguntapartida WHERE id_partida = ? AND respondida = false LIMIT 1");
            $stmt->bind_param("i", $idPartida);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }

        public function actualizaPartida($partida){
            $puntaje = $partida['puntaje'];
            $idPartida = $partida['id_partida'];
            $terminada = $partida['terminada'] ? 1 : 0; // este lo convierto a 0 o 1 pq en la bdd es un tinyint y el stmt no te lo convierte directamente del booleano

            //$sql = "UPDATE `partida` SET `puntaje`= $puntaje,`terminada`= $terminada  WHERE `id_partida`= $idPartida";
            //return $this->conn->actualizar($sql);

            $stmt = $this->conn->prepare("UPDATE `partida` SET `puntaje`= ?,`terminada`= ?  WHERE `id_partida`= ?");
            $stmt->bind_param("iii", $puntaje, $terminada, $idPartida);
            $stmt->execute();
        }

        public function crearNuevaPreguntaPartida($idPartida, $idPregunta, $idUser, $fechaParaGuardar) {
    //        $sql = "INSERT INTO preguntapartida (id_pregunta, id_partida, respondida, acertoElUsuario) VALUES ($idPregunta,$idPartida, 0, 0)";
    //        $this->conn->actualizar($sql);

            $stmt = $this->conn->prepare("INSERT INTO preguntapartida (id_pregunta, id_partida, id_usuario, respondida, acertoElUsuario, fechaEntregada) VALUES (?,?,?, 0, 0,?)");
            $stmt->bind_param("iiis", $idPregunta, $idPartida, $idUser, $fechaParaGuardar);
            $stmt->execute();
        }

    // ver que este metodo creo que no lo uso en mi partidaModel
        /*public function buscarUltimaPartidaPreguntaDeEstaPartida($idPartida){
    //        $ultimoIdInsertado = $this->database->getLastInsert();
    //        $sql = "SELECT * FROM preguntapartida where id_preguntaPartida = $ultimoIdInsertado AND id_partida = $ultimoIdInsertado";
    //        return $this->conn->queryAssoc($sql);
        }*/
    public function actualizaPreguntaPartida($preguntaPartida){
        $idPreguntaPartida = $preguntaPartida['id_preguntaPartida'] ?? null;
        $respondida = isset($preguntaPartida['respondida']) ? (int)$preguntaPartida['respondida'] : null;
        $acertoElUsuario = isset($preguntaPartida['acertoElUsuario']) ? (int)$preguntaPartida['acertoElUsuario'] : null;

        if ($idPreguntaPartida !== null && $respondida !== null && $acertoElUsuario !== null) {
            //$sql = "UPDATE `preguntapartida` SET `respondida` = $respondida, `acertoElUsuario` = $acertoElUsuario WHERE `id_preguntaPartida` = $idPreguntaPartida";
            //$this->conn->actualizar($sql);

            $stmt = $this->conn->prepare("UPDATE `preguntapartida` SET `respondida` = ?, `acertoElUsuario` = ? WHERE `id_preguntaPartida` = ?");
            $stmt->bind_param("iii", $respondida, $acertoElUsuario, $idPreguntaPartida);
            $stmt->execute();
        } else {
            echo "Faltan datos: ";
            echo "ID: " . ($idPreguntaPartida ?? "null") . " ";
            echo "RESP: " . ($respondida ?? "null") . " ";
            echo "ACIER: " . ($acertoElUsuario ?? "null") . " ";
        }
    }


    public function buscarLaUltimaPartidaInsertada($idPartidaABuscar){
//        $sql = "SELECT * FROM partida WHERE id_partida = $idPartidaABuscar";
//        return $this->database->queryAssoc($sql);


        $stmt = $this->conn->prepare("SELECT * FROM partida WHERE id_partida = ?");
        $stmt->bind_param("i", $idPartidaABuscar);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function resetearPreguntaPartidaDeLasPreguntasRespondidasPorEsteUsuario($idUser){
        $stmt = $this->conn->prepare("DELETE FROM preguntapartida WHERE `id_usuario` = ?");
        $stmt->bind_param("i", $idUser);
        $stmt->execute();
    }




    public function obtenerPartidasDelUsuario($idUser){
        $stmt = $this->conn->prepare("SELECT * FROM partida WHERE id_usuario = ? ORDER BY id_partida DESC LIMIT 50");
        $stmt->bind_param("i", $idUser);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    /* --------------------- FIN PARTIDAS------------------------------------------------------------------*/




    /*-------------------- PREGUNTAS ----------------------------------------------------------------------*/

    public function crearEInsertarNuevaPreguntaSugeridaYDevolverElidConElQueSeInserto($categoria, $pregunta){ // este inserta la pregunta con estado (5) "pendiente" (para q el admin la apruebe o la rechace)
        $stmt = $this->conn->prepare("INSERT INTO `pregunta` (`pregunta`, `id_categoria`, `id_dificultad`, `estado`, `apariciones`, `aciertos`) 
                                                            VALUES (?, ? , 1, 5, 0, 0)");
        $stmt->bind_param("si", $pregunta, $categoria);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function actualizarPregunta($pregunta){
        $apariciones = $pregunta['apariciones'];
        $aciertos = $pregunta['aciertos'];
        $idPregunta = $pregunta['id_pregunta'];

        $stmt = $this->conn->prepare("UPDATE `pregunta` SET `apariciones`= ?,`aciertos`= ?  WHERE `id_partida`= ?");
        $stmt->bind_param("iii", $apariciones, $aciertos, $idPregunta);
        $stmt->execute();
    }

    public function obtenerCantidadTotalDePreguntasQueExisten(){
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS total_preguntas FROM `pregunta`");
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['total_preguntas'];
    }
    public function obtenerIdsDeTodasLasPreguntasQueExisten(){
        $stmt = $this->conn->prepare("SELECT id_pregunta FROM `pregunta`");
        $stmt->execute();
        $result = $stmt->get_result();

        $ids = $result->fetch_all(MYSQLI_ASSOC);
        return array_column($ids, 'id_pregunta');
    }

    public function obtenerPregunta($numPreguntaRandom){
        //$sql = "SELECT * FROM pregunta p JOIN categoria c on p.id_categoria = c.id_categoria WHERE p.id_pregunta = $numPreguntaRandom";
        //return $this->conn->queryAssoc($sql);

        $stmt = $this->conn->prepare("SELECT * FROM pregunta p JOIN categoria c on p.id_categoria = c.id_categoria WHERE p.id_pregunta = ?");
        $stmt->bind_param("i", $numPreguntaRandom);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function obtenerTodasLasPreguntasDeLaTablaAprobadasYDesactivadas(){
        $stmt = $this->conn->prepare("SELECT * FROM pregunta p JOIN categoria c on p.id_categoria = c.id_categoria JOIN estado e ON e.id_estado = p.estado WHERE p.estado = 1 or p.estado = 4 ORDER BY id_pregunta ASC");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerDesactivadas(){
        $stmt = $this->conn->prepare("SELECT * FROM pregunta p JOIN categoria c on p.id_categoria = c.id_categoria JOIN estado e ON e.id_estado = p.estado WHERE p.estado = 1 ORDER BY id_pregunta ASC");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerHabilitadas(){
        $stmt = $this->conn->prepare("SELECT * FROM pregunta p JOIN categoria c on p.id_categoria = c.id_categoria JOIN estado e ON e.id_estado = p.estado WHERE p.estado = 4 ORDER BY id_pregunta ASC");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerCantidadDeTodasLasPreguntasDeLaTablaAprobadasYDesactivadas(){
        $stmt = $this->conn->prepare("SELECT COUNT(*) as Total FROM pregunta WHERE estado = 1 or estado = 4");
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function cambiarEstadoDePregunta($idDePreguntaACambiar, $idEstado){
        $stmt = $this->conn->prepare("UPDATE `pregunta` SET `estado`= ? WHERE `id_pregunta`= ?");
        $stmt->bind_param("ii", $idEstado, $idDePreguntaACambiar);
        $stmt->execute();
    }

    public function obtenerRespuestas($idPregunta){
        //$sql = "SELECT * FROM respuesta WHERE id_pregunta = $idPregunta";
        //$resultado = $this->conn->query($sql);
        //return $resultado;

        $stmt = $this->conn->prepare("SELECT * FROM respuesta WHERE id_pregunta = ?");
        $stmt->bind_param("i", $idPregunta);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerRespuestaCorrectaDeEstaPregunta($id_pregunta){
        //$sql = "SELECT * FROM respuesta WHERE id_pregunta = $id_pregunta AND correcta = true";
        //return $this->conn->queryAssoc($sql);

        $stmt = $this->conn->prepare("SELECT * FROM respuesta WHERE id_pregunta = ? AND correcta = true");
        $stmt->bind_param("i", $id_pregunta);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function buscarPreguntasResponidasPorElUsuario($idUser){
        $stmt = $this->conn->prepare("SELECT pp.id_pregunta FROM preguntapartida pp JOIN usuario u ON pp.id_usuario = u.id WHERE u.id = $idUser");
        $stmt->execute();
        $result = $stmt->get_result();

        $ids = [];
        while ($row = $result->fetch_assoc()) {
            $ids[] = $row['id_pregunta'];
        }

        return $ids;
    }

    public function actualizarEstadoPregunta($id_pregunta, $id_estado)
    {
        $sql = "UPDATE pregunta SET estado = ? WHERE id_pregunta = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $id_estado, $id_pregunta); // 'ii' indica dos enteros
        $stmt->execute();
    }
    /*------------------------------------- fin PREGUNTAS ---------------------------------------------------*/




    /*-------------------- INICIO RESPUESTA ----------------------------------------------------------------------*/
    public function crearEInsertarRespuestasParaPreguntaCreada($idDePreguntaInsertada, $opcion1, $opcion2, $opcion3, $opcion4, $respuesta_correcta){
        $stmt = $this->conn->prepare("INSERT INTO `respuesta` (`id_pregunta`, `descripcion`, `correcta`)  
                                                            VALUES (?, ?, 0),
                                                                    (?, ?, 0),
                                                                    (?, ?, 0),
                                                                    (?, ?, 0);");
        $stmt->bind_param("isisisis", $idDePreguntaInsertada, $opcion1, $idDePreguntaInsertada, $opcion2, $idDePreguntaInsertada, $opcion3, $idDePreguntaInsertada, $opcion4);
        $stmt->execute();
    }

    public function setearEstaRespuestaComoCorrectaParaEstaPregunta($idDePreguntaInsertada, $descripcionDeLaRespuestaCorrecta) {
        $descripcionConLike = "%" . $descripcionDeLaRespuestaCorrecta . "%";

        $stmt = $this->conn->prepare("UPDATE `respuesta` SET `correcta` = 1 WHERE `id_pregunta` = ? AND `descripcion` LIKE ?");
        $stmt->bind_param("is", $idDePreguntaInsertada, $descripcionConLike);
        $stmt->execute();
    }
    /*-------------------- FIN RESPUESTA ----------------------------------------------------------------------*/



    /*----------------------------------- RANKING -------------------------------------------------------------*/
    public function obtenerTodosLosUsuarios(){
        $stmt = $this->conn->prepare("SELECT * FROM `usuario`");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerMejorPartidadelUsuario($idUsuario){
        $stmt = $this->conn->prepare("SELECT * FROM partida p JOIN usuario u ON p.id_usuario = u.id WHERE p.id_usuario = ? AND p.terminada = true ORDER BY p.puntaje DESC LIMIT 1");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    /*----------------------------------- FIN RANKING ---------------------------------------------------------*/




    /*----------------------------------- INICIO CATEGORIAS ---------------------------------------------------------*/

    public function obtenerTodasCategorias(){
        $stmt = $this->conn->prepare("SELECT * FROM `categoria`");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /*----------------------------------- FIN  CATEGORIAS---------------------------------------------------------*/




    /*----------------------------------- INICIO REPORTES ---------------------------------------------------------*/
    public function crearReporte($id_pregunta, $id_usuario, $descripcion) {
        $sql = "INSERT INTO reporte (id_pregunta, id_usuario, descripcion) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iis", $id_pregunta, $id_usuario, $descripcion);
        $stmt->execute();
    }

    public function eliminarReporte($idReporte){
        $stmt = $this->conn->prepare("DELETE FROM `reporte` WHERE `id_reporte` = ?");
        $stmt->bind_param("i", $idReporte);
        $stmt->execute();
    }

    public function obtenerTodosLosReportes(){
        $stmt = $this->conn->prepare("SELECT * FROM `reporte`");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerReportePorId($idReporte){
        $stmt = $this->conn->prepare("SELECT * FROM `reporte` WHERE `id_reporte` = ?");
        $stmt->bind_param("i",$idReporte);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    /*----------------------------------- FIN REPORTES ---------------------------------------------------------*/








    public function getError(){
        return $this->error;
    }
    public function __destruct(){
        if ($this->error == ""){
            $this->conn->close();
        }
    }
}
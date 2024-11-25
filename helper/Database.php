<?php
class Database
{

    private $conn;
    private $error = "";

    public function __construct($host, $port, $username, $password, $database)
    {
        try {
            $this->conn = new mysqli($host, $username, $password, $database, $port);
        } catch (Exception $e) {
            $this->error = "Falló la conexión a la base de datos.";
        }
    }

    public function queryConFetchAll($sql)
    {
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

    public function obtenerUltimoIdInsertadoDeTablaUsuario()
    {
        $stmt = $this->conn->prepare("SELECT id FROM usuario ORDER BY id DESC LIMIT 1");
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function buscarEmail($email)
    {
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

    public function registrarUsuario($nombre, $apellido, $nacimiento, $sexo, $ubicacion, $email, $password, $username, $profile_pic)
    {
        /*$fechaRegistro = date('Y-m-d'); // esta seria la fecha de hoy, la pongo aca pq la uso solo para cuando se registra directamente
        $sql = "INSERT INTO `usuario` (`nombre`, `apellido`, `nombreusuario`, `contrasenia`, `email`, `añonacimiento`, `ubicacion`, `fecharegistro`, `fotoperfil`, `sexo`) VALUES ('" . $nombre . "', '" . $apellido . "', '" . $username . "', '" . $password . "', '" . $email . "', '" . $nacimiento . "', '" . $ubicacion . "', '" . $fechaRegistro . "', '" . $profile_pic . "', '" . $sexo . "')";
        return $this->database->insertar($sql);*/

        $fechaRegistro = date('Y-m-d');
        $verificado = 0;
        $musica = 1;
        $sonido = 1;
        $stmt = $this->conn->prepare("INSERT INTO `usuario` (`nombre`, `apellido`, `nombreusuario`, `contrasenia`, `email`, `añonacimiento`, `ubicacion`, `fecharegistro`, `fotoperfil`, `sexo`, `verificado`, `musica`, `sonido`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssiii", $nombre, $apellido, $username, $password, $email, $nacimiento, $ubicacion, $fechaRegistro, $profile_pic, $sexo, $verificado, $musica, $sonido);
        if ($stmt->execute()) {
            return $stmt->insert_id;
        } else {
            return false;
        }
    }

    public function obtenerIdUserPorUserName($username)
    {
        $stmt = $this->conn->prepare("SELECT id FROM usuario WHERE nombreusuario = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function obtenerUsuarioPorId($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM usuario WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function verificarEmail($usuario_id)
    {
        $stmt = $this->conn->prepare("UPDATE usuario SET verificado = 1 WHERE id = ?");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        return $stmt->affected_rows === 1;
    }

    public function getLastInsert()
    {
        return $this->conn->insert_id;
    }

    public function setearMusicaActivadaDelUsuario($activacionDeMusica, $idUsuario){
        $stmt = $this->conn->prepare("UPDATE usuario SET musica = ? WHERE id = ?");
        $stmt->bind_param("ii", $activacionDeMusica, $idUsuario);
        $stmt->execute();
    }

    public function setearSonidoDelUsuario($activacion, $idDelUser){
        $stmt = $this->conn->prepare("UPDATE usuario SET sonido = ? WHERE id = ?");
        $stmt->bind_param("ii", $activacion, $idDelUser);
        $stmt->execute();
    }

    public function actualizarPuntaje($puntaje, $idUsuario)
    {
        $stmt = $this->conn->prepare("UPDATE usuario SET puntaje_usuario = puntaje_usuario + ? WHERE id = ?");
        $stmt->bind_param("ii", $puntaje, $idUsuario);
        $stmt->execute();
    }

    public function actualizarUsuario($usuario)
    {
        $respondidas = $usuario['respondidasusuario'];
        $aciertos = $usuario['aciertosusuario'];
        $dificultad = $usuario['dificultad'];
        $idUser = $usuario['id'];

        $stmt = $this->conn->prepare("UPDATE usuario SET `respondidasusuario` = ?, `aciertosusuario` = ?, `dificultad` = ? WHERE id = ?");
        $stmt->bind_param("iiii", $respondidas, $aciertos, $dificultad, $idUser);
        $stmt->execute();
    }
    /* --------------------- FIN user------------------------------------------------------------------*/


    /* --------------------- INICIO PARTIDAS------------------------------------------------------------------*/
    public function crearPartidaEnCursoParaEsteUser($idUser)
    {
        $fechaActual = new DateTime();
        $fechaFormateada = $fechaActual->format('Y-m-d');

        $stmt = $this->conn->prepare("INSERT INTO partida (id_usuario, puntaje, terminada, fechapartida) VALUES (?, 0, FALSE, ?)");
        $stmt->bind_param("is", $idUser, $fechaFormateada);

        if ($stmt->execute()) {
            return $this->conn->insert_id;
        } else {
            return false;
        }

    }

    public function buscarSiHayUnaPartidaEnCursoParaEsteUser($idUser)
    {
        $stmt = $this->conn->prepare("SELECT * FROM partida WHERE id_usuario = ? AND terminada = 0");
        $stmt->bind_param("i", $idUser);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function buscarUltimaPreguntaNoResponididaDeLaPartida($idPartida)
    {
        $stmt = $this->conn->prepare("SELECT * FROM preguntapartida WHERE id_partida = ? AND respondida = false LIMIT 1");
        $stmt->bind_param("i", $idPartida);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function actualizaPartida($partida)
    {
        $puntaje = $partida['puntaje'];
        $idPartida = $partida['id_partida'];
        $terminada = $partida['terminada'] ? 1 : 0; // este lo convierto a 0 o 1 pq en la bdd es un tinyint y el stmt no te lo convierte directamente del booleano

        $stmt = $this->conn->prepare("UPDATE `partida` SET `puntaje`= ?,`terminada`= ?  WHERE `id_partida`= ?");
        $stmt->bind_param("iii", $puntaje, $terminada, $idPartida);
        $stmt->execute();
    }

    public function crearNuevaPreguntaPartida($idPartida, $idPregunta, $idUser, $fechaParaGuardar)
    {
        $stmt = $this->conn->prepare("INSERT INTO preguntapartida (id_pregunta, id_partida, id_usuario, respondida, acertoElUsuario, fechaEntregada) VALUES (?,?,?, 0, 0,?)");
        $stmt->bind_param("iiis", $idPregunta, $idPartida, $idUser, $fechaParaGuardar);
        $stmt->execute();
    }

    public function actualizaPreguntaPartida($preguntaPartida)
    {
        $idPreguntaPartida = $preguntaPartida['id_preguntaPartida'] ?? null;
        $respondida = isset($preguntaPartida['respondida']) ? (int)$preguntaPartida['respondida'] : null;
        $acertoElUsuario = isset($preguntaPartida['acertoElUsuario']) ? (int)$preguntaPartida['acertoElUsuario'] : null;

        if ($idPreguntaPartida !== null && $respondida !== null && $acertoElUsuario !== null) {

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


    public function buscarLaUltimaPartidaInsertada($idPartidaABuscar)
    {
        $stmt = $this->conn->prepare("SELECT * FROM partida WHERE id_partida = ?");
        $stmt->bind_param("i", $idPartidaABuscar);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function resetearPreguntaPartidaDeLasPreguntasRespondidasPorEsteUsuario($idUser)
    {
        $stmt = $this->conn->prepare("DELETE FROM preguntapartida WHERE `id_usuario` = ?");
        $stmt->bind_param("i", $idUser);
        $stmt->execute();
    }


    public function obtenerPartidasDelUsuario($idUser)
    {
        $stmt = $this->conn->prepare("SELECT * FROM partida WHERE id_usuario = ? ORDER BY id_partida DESC LIMIT 50");
        $stmt->bind_param("i", $idUser);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    /* --------------------- FIN PARTIDAS------------------------------------------------------------------*/


    /*-------------------- PREGUNTAS ----------------------------------------------------------------------*/

    public function crearEInsertarNuevaPreguntaSugeridaYDevolverElidConElQueSeInserto($categoria, $pregunta, $idEstadoQueDebeQuedarLaPregunta)
    { // este inserta la pregunta con estado (5) "pendiente" (para q el admin la apruebe o la rechace)
        $stmt = $this->conn->prepare("INSERT INTO `pregunta` (`pregunta`, `id_categoria`, `dificultad`, `estado`, `apariciones`, `aciertos`) 
                                                            VALUES (?, ? , 1, ?, 0, 0)");
        $stmt->bind_param("sii", $pregunta, $categoria, $idEstadoQueDebeQuedarLaPregunta);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function actualizarPregunta($pregunta)
    {
        $apariciones = $pregunta['apariciones'];
        $aciertos = $pregunta['aciertos'];
        $dificultad = $pregunta['dificultad'];
        $idPregunta = $pregunta['id_pregunta'];

        $stmt = $this->conn->prepare("UPDATE `pregunta` SET `apariciones`= ?,`aciertos`= ?, `dificultad`= ? WHERE `id_pregunta`= ?");
        $stmt->bind_param("iiii", $apariciones, $aciertos, $dificultad, $idPregunta);
        $stmt->execute();
    }

    public function actualizarPreguntaEditada($idPregunta, $pregunta, $categoria, $idEstado)
    {
        $stmt = $this->conn->prepare("UPDATE `pregunta` SET `pregunta`= ?,`id_categoria`= ?, `estado`= ? WHERE `id_pregunta`= ?");
        $stmt->bind_param("siii", $pregunta, $categoria, $idEstado, $idPregunta);
        $stmt->execute();
    }

    public function obtenerCantidadTotalDePreguntasQueExisten()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS total_preguntas FROM `pregunta`");
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['total_preguntas'];
    }

    public function obtenerIdsDeTodasLasPreguntasHabilitadasYOReportadasQueExistenDeEstaDificultad($dificultadUser)
    {
        $stmt = $this->conn->prepare("SELECT id_pregunta FROM `pregunta` WHERE pregunta.dificultad = ? AND pregunta.estado = 4 OR pregunta.estado = 2");
        $stmt->bind_param("i", $dificultadUser);
        $stmt->execute();
        $result = $stmt->get_result();

        $ids = $result->fetch_all(MYSQLI_ASSOC);
        return array_column($ids, 'id_pregunta');
    }

    public function obtenerPregunta($numPreguntaRandom)
    {
        //$sql = "SELECT * FROM pregunta p JOIN categoria c on p.id_categoria = c.id_categoria WHERE p.id_pregunta = $numPreguntaRandom";
        //return $this->conn->queryAssoc($sql);

        $stmt = $this->conn->prepare("SELECT * FROM pregunta p JOIN categoria c on p.id_categoria = c.id_categoria WHERE p.id_pregunta = ?");
        $stmt->bind_param("i", $numPreguntaRandom);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function obtenerTodasLasPreguntasDeLaTablaAprobadasYDesactivadas()
    {
        $stmt = $this->conn->prepare("SELECT * FROM pregunta p JOIN categoria c on p.id_categoria = c.id_categoria JOIN estado e ON e.id_estado = p.estado WHERE p.estado = 1 or p.estado = 4 ORDER BY id_pregunta ASC");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerDesactivadas()
    {
        $stmt = $this->conn->prepare("SELECT * FROM pregunta p JOIN categoria c on p.id_categoria = c.id_categoria JOIN estado e ON e.id_estado = p.estado WHERE p.estado = 1 ORDER BY id_pregunta ASC");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerHabilitadas()
    {
        $stmt = $this->conn->prepare("SELECT * FROM pregunta p JOIN categoria c on p.id_categoria = c.id_categoria JOIN estado e ON e.id_estado = p.estado WHERE p.estado = 4 ORDER BY id_pregunta ASC");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerCantidadDeTodasLasPreguntasDeLaTablaAprobadasYDesactivadas()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as Total FROM pregunta WHERE estado = 1 or estado = 4");
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function cambiarEstadoDePregunta($idDePreguntaACambiar, $idEstado)
    {
        $stmt = $this->conn->prepare("UPDATE `pregunta` SET `estado`= ? WHERE `id_pregunta`= ?");
        $stmt->bind_param("ii", $idEstado, $idDePreguntaACambiar);
        $stmt->execute();
    }

    public function editarRespuesta($idOpcionAActualizar, $valorDeLaOpcionAActualizar)
    {
        $stmt = $this->conn->prepare("UPDATE `respuesta` SET `descripcion`= ?, `correcta`= 0 WHERE `id_respuesta`= ?");
        $stmt->bind_param("si", $valorDeLaOpcionAActualizar, $idOpcionAActualizar);
        $stmt->execute();
    }

    public function obtenerRespuestas($idPregunta)
    {
        $stmt = $this->conn->prepare("SELECT * FROM respuesta WHERE id_pregunta = ?");
        $stmt->bind_param("i", $idPregunta);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerRespuestaCorrectaDeEstaPregunta($id_pregunta)
    {
        $stmt = $this->conn->prepare("SELECT * FROM respuesta WHERE id_pregunta = ? AND correcta = true");
        $stmt->bind_param("i", $id_pregunta);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function buscarPreguntasHabilitadasYOReportadasResponidasPorElUsuarioDeSuDificultad($idUser, $dificultad)
    {
        $stmt = $this->conn->prepare("SELECT pp.id_pregunta FROM preguntapartida pp JOIN usuario u ON pp.id_usuario = u.id JOIN pregunta p ON p.id_pregunta = pp.id_pregunta WHERE u.id = ? AND p.dificultad = ? AND p.estado = 4 OR p.estado = 2");
        $stmt->bind_param("ii", $idUser, $dificultad);
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

    public function obtenerTodasSugeridas()
    {
        $stmt = $this->conn->prepare("SELECT * FROM pregunta p JOIN categoria c ON p.id_categoria = c.id_categoria WHERE estado = 5 ORDER BY id_pregunta ASC");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerPreguntaSugerida($idPreguntaSugerida)
    {
        $stmt = $this->conn->prepare("SELECT * FROM pregunta p JOIN categoria c ON p.id_categoria = c.id_categoria WHERE id_pregunta = ? AND estado = 5");
        $stmt->bind_param("i", $idPreguntaSugerida);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function aprobarSugerencia($idPreguntaSugerida)
    {
        $stmt = $this->conn->prepare("UPDATE `pregunta` SET `estado` = 4 WHERE id_pregunta = ?");
        $stmt->bind_param("i", $idPreguntaSugerida);
        $stmt->execute();
    }

    public function rechazarSugerencia($idPreguntaSugerida)
    {
        $stmt = $this->conn->prepare("UPDATE `pregunta` SET `estado` = 3 WHERE id_pregunta = ?");
        $stmt->bind_param("i", $idPreguntaSugerida);
        $stmt->execute();
    }

    public function habilitarTodasLasPreguntasDesactivadas()
    {
        $stmt = $this->conn->prepare("UPDATE `pregunta` SET `estado` = 4 WHERE estado = 1");
        $stmt->execute();
    }

    public function desactivarTodasLasPreguntasHabilitadasYReportadas()
    {
        $stmt = $this->conn->prepare("UPDATE `pregunta` SET `estado` = 1 WHERE estado = 4 OR estado = 2");
        $stmt->execute();
    }
    /*------------------------------------- fin PREGUNTAS ---------------------------------------------------*/


    /*-------------------- INICIO RESPUESTA ----------------------------------------------------------------------*/
    public function crearEInsertarRespuestasParaPreguntaCreada($idDePreguntaInsertada, $opcion1, $opcion2, $opcion3, $opcion4, $respuesta_correcta)
    {
        $stmt = $this->conn->prepare("INSERT INTO `respuesta` (`id_pregunta`, `descripcion`, `correcta`)  
                                                            VALUES (?, ?, 0),
                                                                    (?, ?, 0),
                                                                    (?, ?, 0),
                                                                    (?, ?, 0);");
        $stmt->bind_param("isisisis", $idDePreguntaInsertada, $opcion1, $idDePreguntaInsertada, $opcion2, $idDePreguntaInsertada, $opcion3, $idDePreguntaInsertada, $opcion4);
        $stmt->execute();
    }

    public function setearEstaRespuestaComoCorrectaParaEstaPregunta($idDePreguntaInsertada, $descripcionDeLaRespuestaCorrecta)
    {
        $descripcionConLike = "%" . $descripcionDeLaRespuestaCorrecta . "%";

        $stmt = $this->conn->prepare("UPDATE `respuesta` SET `correcta` = 1 WHERE `id_pregunta` = ? AND `descripcion` LIKE ?");
        $stmt->bind_param("is", $idDePreguntaInsertada, $descripcionConLike);
        $stmt->execute();
    }
    /*-------------------- FIN RESPUESTA ----------------------------------------------------------------------*/


    /*----------------------------------- RANKING -------------------------------------------------------------*/
    public function obtenerTodosLosUsuarios()
    {
        $stmt = $this->conn->prepare("SELECT * FROM `usuario`");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerMejorPartidadelUsuario($idUsuario)
    {
        $stmt = $this->conn->prepare("SELECT * FROM partida p JOIN usuario u ON p.id_usuario = u.id WHERE p.id_usuario = ? AND p.terminada = true ORDER BY p.puntaje DESC LIMIT 1");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    /*----------------------------------- FIN RANKING ---------------------------------------------------------*/


    /*----------------------------------- INICIO CATEGORIAS ---------------------------------------------------------*/

    public function obtenerTodasCategorias()
    {
        $stmt = $this->conn->prepare("SELECT * FROM `categoria`");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /*----------------------------------- FIN  CATEGORIAS---------------------------------------------------------*/


    /*----------------------------------- INICIO REPORTES ---------------------------------------------------------*/
    public function crearReporte($id_pregunta, $id_usuario, $descripcion)
    {
        $sql = "INSERT INTO reporte (id_pregunta, id_usuario, descripcion) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iis", $id_pregunta, $id_usuario, $descripcion);
        $stmt->execute();
    }

    public function eliminarReporte($idReporte)
    {
        $stmt = $this->conn->prepare("DELETE FROM `reporte` WHERE `id_reporte` = ?");
        $stmt->bind_param("i", $idReporte);
        $stmt->execute();
    }

    public function obtenerTodosLosReportes(){
        $stmt = $this->conn->prepare("SELECT * FROM `reporte`");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerReportePorId($idReporte)
    {
        $stmt = $this->conn->prepare("SELECT * FROM `reporte` WHERE `id_reporte` = ?");
        $stmt->bind_param("i", $idReporte);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    /*----------------------------------- FIN  REPORTES---------------------------------------------------------*/


    public function getError()
    {
        return $this->error;
    }

    public function __destruct()
    {
        if ($this->error == "") {
            $this->conn->close();
        }
    }


    /*----------------------------------- GRAFICOS DE ADMIN ---------------------------------------------------------*/

    public function obtenerCantidadDePreguntasPorCategoria()
    {
        $stmt = $this->conn->prepare("
        SELECT 
            c.nombre AS categoria,
            COUNT(p.id_pregunta) AS cantidadPreguntas
        FROM 
            categoria c
        LEFT JOIN 
            pregunta p 
        ON 
            c.id_categoria = p.id_categoria
        GROUP BY 
            c.id_categoria
        ORDER BY 
            cantidadPreguntas DESC
    ");
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $datos = [];

        foreach ($resultado as $registro) {
            $categoria = $registro['categoria'];
            $cantidadPreguntas = intval($registro['cantidadPreguntas']);
            $datos[] = [
                'dato' => $categoria,
                'valor' => $cantidadPreguntas
            ];
        }

        return $datos;
    }

    public function obtenerCantidadDePreguntasPorCategoriaConFiltro($fechaDesde = null, $fechaHasta = null)
    {
        // Construir la consulta base
        $query = "
        SELECT 
            c.nombre AS categoria,
            COUNT(p.id_pregunta) AS cantidadPreguntas
        FROM 
            categoria c
        LEFT JOIN 
            pregunta p 
        ON 
            c.id_categoria = p.id_categoria
        WHERE
            1=1
    ";

        // Filtrar por fecha desde
        if ($fechaDesde) {
            $query .= " AND p.fecha_alta >= ?";
        }

        // Filtrar por fecha hasta
        if ($fechaHasta) {
            $query .= " AND p.fecha_alta <= ?";
        }

        // Agrupar por categoria
        $query .= " GROUP BY c.id_categoria ORDER BY cantidadPreguntas DESC";

        // Preparar la consulta
        $stmt = $this->conn->prepare($query);

        // Enlazar los parámetros según las fechas que recibimos
        if ($fechaDesde && $fechaHasta) {
            // Si ambas fechas son proporcionadas, enlazamos ambas
            $stmt->bind_param("ss", $fechaDesde, $fechaHasta);
        } elseif ($fechaDesde) {
            // Si solo se recibe fechaDesde, solo enlazamos esa
            $stmt->bind_param("s", $fechaDesde);
        } elseif ($fechaHasta) {
            // Si solo se recibe fechaHasta, solo enlazamos esa
            $stmt->bind_param("s", $fechaHasta);
        }

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el resultado
        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Preparar los datos para el formato deseado
        $datos = [];

        foreach ($resultado as $registro) {
            $categoria = $registro['categoria'];
            $cantidadPreguntas = intval($registro['cantidadPreguntas']);
            $datos[] = [
                'dato' => $categoria,
                'valor' => $cantidadPreguntas
            ];
        }

        return $datos;
    }


    public function obtenerCantidadDeJugadores()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS cantidad FROM usuario WHERE rango = 3");
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        return intval($resultado['cantidad']);
    }

    public function obtenerCantidadDeJugadoresPorSexo()
    {


        $stmt = $this->conn->prepare("SELECT
                CASE
                    WHEN sexo = 'm' THEN 'Masculino'
                    WHEN sexo = 'f' THEN 'Femenino'
                    WHEN sexo = 'x' THEN 'Otro'
                END AS sexo_filtrado,
                COUNT(*) AS cantidadUsuarios
                FROM usuario
                GROUP BY sexo_filtrado");
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $datos = [];

        foreach ($resultado as $registro) {
            $sexo = $registro['sexo_filtrado'];
            $cantidadUsuarios = intval($registro['cantidadUsuarios']);
            $datos[] = [
                'dato' => $sexo,
                'valor' => $cantidadUsuarios
            ];
        }
        return $datos;
    }

    public function obtenerCantidadDeJugadoresPorSexoConFiltro($desde = null, $hasta = null)
    {
        // Base de la consulta SQL
        $query = "SELECT CASE WHEN sexo = 'm' THEN 'Masculino' WHEN sexo = 'f' THEN 'Femenino' WHEN sexo = 'x' THEN 'Otro' END AS sexo_filtrado, COUNT(*) AS cantidadUsuarios FROM usuario";

        $params = [];
        $conditions = [];

        // Condiciones dinámicas para filtrar por fechas
        if ($desde !== null) {
            $conditions[] = "fecharegistro >= ?";
            $params[] = $desde;
        }
        if ($hasta !== null) {
            $conditions[] = "fecharegistro <= ?";
            $params[] = $hasta;
        }

        // Si hay condiciones, agrégalas a la consulta
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        // Agrupación por sexo
        $query .= " GROUP BY sexo_filtrado";

        // Preparar y ejecutar la consulta
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param(str_repeat('s', count($params)), ...$params);
        $stmt->execute();

        // Obtener los resultados
        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Formatear los datos en el array esperado
        $datos = [];
        foreach ($resultado as $registro) {
            $sexo = $registro['sexo_filtrado'];
            $cantidadUsuarios = intval($registro['cantidadUsuarios']);
            $datos[] = [
                'dato' => $sexo,
                'valor' => $cantidadUsuarios
            ];
        }

        return $datos;
    }


    public function obtenerCantidadDePartidasPorUsuario()
    {
        $stmt = $this->conn->prepare("
        SELECT 
            u.nombreusuario AS usuario,
            COUNT(p.id_partida) AS cantidadPartidas
        FROM 
            usuario u
        LEFT JOIN 
            partida p 
        ON 
            u.id = p.id_usuario
        WHERE 
            u.rango = 3  -- Filtra solo los usuarios con rango 3 (jugadores)
        GROUP BY 
            u.id
        ORDER BY 
            cantidadPartidas DESC
    ");
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $datos = [];

        foreach ($resultado as $registro) {
            $usuario = $registro['usuario'];
            $cantidadPartidas = intval($registro['cantidadPartidas']);
            $datos[] = [
                'dato' => $usuario,
                'valor' => $cantidadPartidas
            ];
        }

        return $datos;
    }

    public function obtenerCantidadDePartidasJugadasPorUsuarioConFiltro($fechaDesde, $fechaHasta){
// Inicializar la consulta base
        $query = "
        SELECT 
            u.nombreusuario AS usuario,
            COUNT(p.id_partida) AS cantidadPartidas
        FROM 
            usuario u
        LEFT JOIN 
            partida p 
        ON 
            u.id = p.id_usuario
        WHERE 
            u.rango = 3";  // Filtra solo los usuarios con rango 3 (jugadores)

        // Filtrar por fecha desde (si está presente)
        if (!empty($fechaDesde)) {
            $query .= " AND p.fechapartida >= ?";  // Asegura que la fecha de partida sea mayor o igual a $fechaDesde
        }

        // Filtrar por fecha hasta (si está presente)
        if (!empty($fechaHasta)) {
            $query .= " AND p.fechapartida <= ?";  // Asegura que la fecha de partida sea menor o igual a $fechaHasta
        }

        // Completar la consulta
        $query .= "
        GROUP BY 
            u.id
        ORDER BY 
            cantidadPartidas DESC
    ";

        // Preparar la sentencia SQL
        $stmt = $this->conn->prepare($query);

        // Asociar los parámetros de fecha a la sentencia SQL
        if (!empty($fechaDesde) && !empty($fechaHasta)) {
            // Si ambas fechas están presentes
            $stmt->bind_param("ss", $fechaDesde, $fechaHasta);
        } elseif (!empty($fechaDesde)) {
            // Si solo la fecha desde está presente
            $stmt->bind_param("s", $fechaDesde);
        } elseif (!empty($fechaHasta)) {
            // Si solo la fecha hasta está presente
            $stmt->bind_param("s", $fechaHasta);
        }

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el resultado
        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $datos = [];

        // Procesar el resultado para devolverlo en el formato requerido
        foreach ($resultado as $registro) {
            $usuario = $registro['usuario'];
            $cantidadPartidas = intval($registro['cantidadPartidas']);
            $datos[] = [
                'dato' => $usuario,
                'valor' => $cantidadPartidas
            ];
        }

        // Retornar los datos procesados
        return $datos;
    }

    public function obtenerPreguntasHabilitadas()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS cantidad FROM pregunta p 
                                  JOIN categoria c ON p.id_categoria = c.id_categoria 
                                  JOIN estado e ON e.id_estado = p.estado 
                                  WHERE p.estado = 4");
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        return intval($resultado['cantidad']);
    }




}


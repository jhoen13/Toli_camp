<?php
require_once("../db/conexion.php");
$db = new Database();
$con = $db->conectar();
session_start();

if (isset($_POST["btn-ingresar"])) {

    // ESTOS DATOS DEL USUARIO VIENEN DEL FORMULARIO Y LA DB
    $email = $_POST['correo_electronico'];
    $contra = $_POST['password'];

    // SE SACA LA HORA EN TIEMPO REAL
    date_default_timezone_set('America/Bogota');
    $fecha_actual = date('Y-m-d');
    $hora_actual = date('H:i:s');

    // SE REALIZA UNA CONSULTA PARA SABER SI LA PERSONA ESTA EN MODO 1 ACTIVO
    $consulta = $con->prepare("SELECT * FROM usuarios WHERE correo_electronico = :email AND id_estado = 1");
    $consulta->execute([':email' => $email]);
    $consul = $consulta->fetch();

    // SE SACA LA VARIANLE SESSION DOCUMENTO DEL USUARIO
    $documento = null; // Inicializar la variable
    if ($consul) {
        $_SESSION['document'] = $consul['documento'];
        $documento = $_SESSION['document'];
    }

    // SE INSERTAN DATOS A LA TABLA DE INGRESO
    if ($documento) { // Asegúrate de que $documento esté definido
        $consulta2 = $con->prepare("SELECT * FROM ingreso INNER JOIN usuarios ON ingreso.documento=usuarios.documento WHERE ingreso.documento= :documento");
        $consulta2->execute([':documento' => $documento]);

        // Insertar un nuevo registro en la tabla 'ingreso'
        $consulta3 = $con->prepare("INSERT INTO ingreso (codi_ingre, documento, fecha_ingre, hora_ingre) VALUES (:id_ingreso, :documento, :fecha_actual, :hora_actual)");

        // Obtener el próximo id_ingreso autoincremental antes de la inserción
        $next_id = null;
        $consulta_next_id = $con->query("SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'toli_camp' AND TABLE_NAME = 'ingreso'");
        if ($row = $consulta_next_id->fetch(PDO::FETCH_ASSOC)) {
            $next_id = $row['AUTO_INCREMENT'];
        }

        // Ejecutar la inserción con el próximo "id_ingreso" autoincremental como valor para "codi_ingre"
        if ($next_id !== null) {
            $consulta3->execute([':id_ingreso' => $next_id, ':documento' => $documento, ':fecha_actual' => $fecha_actual, ':hora_actual' => $hora_actual]);
        }
    }
    /* Set the "cost" parameter to 12. */
    $options = ['cost' => 12];

    if ($consul) {

        if (password_verify($contra, $consul['password'])) {
            /* The password is correct. */

            /* Check if the hash needs to be created again. */
            if (password_needs_rehash($consul['password'], PASSWORD_DEFAULT, $options)) {
                $hash = password_hash($contra, PASSWORD_DEFAULT, $options);

                /* Update the password hash on the database. */
                $query = 'UPDATE usuarios SET password = :passwd WHERE documento = :id';
                $values = [':passwd' => $hash, ':id' => $consul['documento']];

                try {
                    $res = $con->prepare($query);
                    $res->execute($values);
                } catch (PDOException $e) {
                    /* Query error. */
                    echo 'Query error.';
                    die();
                }
            }

            $_SESSION['document'] = $consul['documento'];
            $_SESSION['name'] = $consul['nombre'];
            $_SESSION['email'] = $consul['correo_electronico'];
            $_SESSION['roles'] = $consul['id_rol'];
            $_SESSION['pass'] = $consul['password'];


            $redirectLocation = '';

            switch ($_SESSION['roles']) {
                case 1:
                    $redirectLocation = "../views/models/admin/index-admin.php";
                    break;
                case 2:
                    $redirectLocation = "../views/models/user/index-user.php";
                    break;
                case 3:
                    $redirectLocation = "../views/models/distri_mino/index-vende.php";
                    break;
                case 4:
                    $redirectLocation = "../views/models/distri_mayo/index-distriMayo.php";
                    break;
                default:
                    header("Location:../views/auth/error_lo.php");
                    exit();
            }

            header("Location: $redirectLocation");
            exit();
        } else {
            echo '<script>alert("Ingresaste datos erroneos");</script>';
            echo '<script>window.location="../views/auth/error_lo.php";</script>';
        }
    } else {
        echo '<script>alert("Ingresaste datos erroneos");</script>';
        echo '<script>window.location="../views/auth/error_lo.php";</script>';
    }
}

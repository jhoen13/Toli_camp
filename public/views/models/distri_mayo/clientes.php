<?php
session_start();
require_once("../../../db/conexion.php");
$db = new Database();
$con = $db->conectar();

// Consulta para obtener los usuarios con el rol "usuario" (ID 2)
$rolUsuario = 2;
$sql = "SELECT u.*, r.tipo_rol FROM usuarios u JOIN roles r ON u.id_rol = r.id_rol WHERE u.id_rol = :id_rol";
$stmt = $con->prepare($sql);
$stmt->bindParam(':id_rol', $rolUsuario);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Listado de Usuarios</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .volver-button {
            margin-top: 20px;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }

        .volver-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <h2>Listado de Usuarios con Rol "Usuario"</h2>
    <table>
        <thead>
            <tr>
                <th>Documento</th>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Celular</th>
                <th>Género</th>
                <th>Rol</th>
                <!-- Agrega más columnas según tus necesidades -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario) : ?>
                <tr>
                    <td><?php echo $usuario['documento']; ?></td>
                    <td><?php echo $usuario['nombre']; ?></td>
                    <td><?php echo $usuario['direccion']; ?></td>
                    <td><?php echo $usuario['celular']; ?></td>
                    <td><?php echo $usuario['id_genero']; ?></td>
                    <td><?php echo $usuario['tipo_rol']; ?></td>
                    <!-- Agrega más celdas según tus necesidades -->
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Botón de volver -->
    <a href="javascript:history.back()" class="volver-button">Volver</a>
</body>

</html>

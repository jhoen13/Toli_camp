<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validar que los campos existen
    if (isset($_POST['correo']) && isset($_POST['sugerencia'])) {
        $correo = $_POST['correo'];
        $sugerencia = $_POST['sugerencia'];

        // Asunto y mensaje del correo
        $titulo = "Sugerencias";
        $mensaje = $sugerencia;

        // Cabeceras del correo
        $cabeceras = "From: tolicamp5@gmail.com\r\n";
        $cabeceras .= "Reply-To: $correo\r\n";
        $cabeceras .= "Content-type: text/html; charset=UTF-8\r\n";

        // Intentar enviar el correo
        if (mail("tolicamp5@gmail.com", $titulo, $mensaje, $cabeceras)) {
            echo '<script>alert("Correo enviado con éxito");</script>';
            // Puedes redirigir a otra página si es necesario
            echo '<script>window.location="../../../index.html"</script>';
        } else {
            echo '<script>alert("ERROR, inténtelo nuevamente");</script>';
        }
    } else {
        echo '<script>alert("ERROR, campos de formulario incompletos");</script>';
    }
}

?>


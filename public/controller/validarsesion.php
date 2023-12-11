<?php
//Archivo que permite validar la sesion
session_start();
if(!isset($_SESSION['email']) || !isset($_SESSION['document']) || !isset($_SESSION['roles']))
{
    header("location:../views/auth/login.php");
    exit;
}
?>
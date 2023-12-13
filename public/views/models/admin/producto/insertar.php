<?php 

	$conexion=mysqli_connect('localhost','root','','toli_Camp');
	
	$nom_produc=$_POST['nom_produc'];

	$sql="INSERT into t_productos (nom_produc)
						values ('$nom_produc')";
	$result=mysqli_query($conexion,$sql);

	$id=mysqli_insert_id($conexion);//obtenemos el ultimo id agregado
	$codigo=$id.date('is');//milisegundos
	$sql="UPDATE t_productos 
			set barcode='$codigo'
			where id_producto='$id'";
	$result=mysqli_query($conexion,$sql);

	header("Location:../index.php");
 ?>
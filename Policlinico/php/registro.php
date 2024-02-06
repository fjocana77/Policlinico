<?php
// Se utiliza para llamar al archivo que contine la conexion a la base de datos
require 'conexion.php';

// Validamos que el formulario y que el boton registro haya sido presionado
if(isset($_POST['registro'])) {

// Obtener los valores enviados por el formulario
$usuario = $_POST['nombre_usuario'];
$contrasena = $_POST['contrasena'];
$correo = $_POST['email'];

// Insertamos los datos en la base de datos
$sql = "INSERT INTO usuarios (id, nombre_usuario, contrasena, email) VALUES (null, '$usuario', '$contrasena', '$correo')";
$resultado = mysqli_query($conexion,$sql);
	if($resultado) {
		// Iserción correcta
		echo "¡Se insertaron los datos correctamente!";
	} else {
		// Iserción fallida
		echo "¡No se puede insertar la informacion!"."<br>";
		echo "Error: " . $sql . "<br>" . mysqli_error($conexion);
	}
}
?>

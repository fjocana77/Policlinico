<?php
// Se utiliza para llamar al archivo que contiene la conexión a la base de datos
require 'conexion.php';

// Validamos que el formulario y que el botón login haya sido presionado
if(isset($_POST['login'])) {

    // Obtener los valores enviados por el formulario
    $usuario = $_POST['nombre_usuario'];
    $contrasena = $_POST['contrasena'];

    // Consulta preparada para prevenir SQL injection
    $sql = "SELECT * FROM usuarios WHERE nombre_usuario = ? AND contrasena = ?";
    
    // Preparar la consulta
    $stmt = mysqli_prepare($conexion, $sql);

    // Vincular parámetros
    mysqli_stmt_bind_param($stmt, "ss", $usuario, $contrasena);

    // Ejecutar la consulta
    mysqli_stmt_execute($stmt);

    // Obtener el resultado
    $resultado = mysqli_stmt_get_result($stmt);

    // Obtener el número de filas
    $numero_registros = mysqli_num_rows($resultado);

    // Cerrar la consulta preparada
    mysqli_stmt_close($stmt);

    if($numero_registros != 0) {
        // Inicio de sesión exitoso

        // Inicia sesión (si no se ha iniciado ya)
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Guarda el nombre de usuario en la sesión 
        $_SESSION['nombre_usuario'] = $usuario;

        // Redirigir a la página de citas
        header("Location: agenda.php");
        exit(); // Asegura que no se ejecuten más instrucciones después de la redirección
    } else {
        // Credenciales inválidas
        echo "Credenciales inválidas. Por favor, verifica tu nombre de usuario y/o contraseña."."<br>";
        echo "Error: " . $sql . "<br>" . mysqli_error($conexion);
    }
}
?>

<?php
// Archivo de conexión a la base de datos (conexion.php)
require 'conexion.php';

// Verificar si se ha enviado el formulario para editar una cita
if (isset($_POST['editar_cita'])) {
    $id_cita = $_POST['id_cita'];
    $nueva_fecha_cita = $_POST['nueva_fecha_cita'];
    $nueva_hora_cita = $_POST['nueva_hora_cita'];
    $nueva_descripcion = $_POST['nueva_descripcion'];

    // Actualizar la cita en la base de datos
    $sql = "UPDATE citas SET fecha_cita = ?, hora_cita = ?, descripcion = ? WHERE id = ?";
    
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "sssi", $nueva_fecha_cita, $nueva_hora_cita, $nueva_descripcion, $id_cita);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Redirigir después de procesar el formulario
    header("Location: agenda.php");
    exit();
}
?>

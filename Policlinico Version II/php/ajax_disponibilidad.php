<?php
require 'conexion.php';

if (isset($_POST['id_doctor'])) {
    $id_doctor = $_POST['id_doctor'];

    // Consultar disponibilidad de fechas y horas para el doctor seleccionado
    $sql_disponibilidad = "SELECT DISTINCT fecha_cita FROM citas WHERE id_doctor = ? ORDER BY fecha_cita";
    $stmt = mysqli_prepare($conexion, $sql_disponibilidad);
    mysqli_stmt_bind_param($stmt, "i", $id_doctor);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $fechas_disponibles = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

    // Enviar las fechas disponibles como respuesta
    echo json_encode($fechas_disponibles);

    mysqli_stmt_close($stmt);
}

mysqli_close($conexion);
?>

<?php
// Archivo de conexión a la base de datos (conexion.php)
require 'conexion.php';


function obtenerNombreUsuario($conexion, $id_usuario) {
    $sql_usuario = "SELECT nombre_usuario FROM usuarios WHERE id = ?";
    $stmt_usuario = mysqli_prepare($conexion, $sql_usuario);
    mysqli_stmt_bind_param($stmt_usuario, "i", $id_usuario);
    mysqli_stmt_execute($stmt_usuario);
    mysqli_stmt_bind_result($stmt_usuario, $nombre_usuario);
    mysqli_stmt_fetch($stmt_usuario);
    mysqli_stmt_close($stmt_usuario);
    return $nombre_usuario;
}


// Verificar si se ha enviado el formulario para programar una cita
if (isset($_POST['agendar_cita'])) {
    $id_usuario = $_POST['id_usuario'];
    $id_doctor = $_POST['id_doctor'];
    $fecha_cita = $_POST['fecha_cita'];
    $hora_cita = $_POST['hora_cita'];
    $descripcion = $_POST['descripcion'];

    // Insertar la cita en la base de datos
    $sql = "INSERT INTO citas (id_usuario, id_doctor, fecha_cita, hora_cita, descripcion) VALUES (?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "iisss", $id_usuario, $id_doctor, $fecha_cita, $hora_cita, $descripcion);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Redirigir después de procesar el formulario
    header("Location: agenda.php");
    exit();
}

// Consultar las citas programadas con el nombre del doctor
$sql = "SELECT c.id, c.id_usuario, c.fecha_cita, c.hora_cita, c.descripcion, d.nombre_doctor
        FROM citas c
        INNER JOIN doctores d ON c.id_doctor = d.id_doctor";
$resultado = mysqli_query($conexion, $sql);
$citas = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

// Obtener la información de los usuarios para mostrar en el formulario
$sql_usuarios = "SELECT id, nombre_usuario FROM usuarios";
$resultado_usuarios = mysqli_query($conexion, $sql_usuarios);
$usuarios = mysqli_fetch_all($resultado_usuarios, MYSQLI_ASSOC);

$sql_doctores = "SELECT * FROM doctores";
$resultado_doctores = mysqli_query($conexion, $sql_doctores);
$doctores = mysqli_fetch_all($resultado_doctores, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda de Citas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">Agenda de Citas</h1>

    <!-- Formulario para programar una cita -->
    <form method="post" action="agenda.php" class="mb-4">
        <div class="form-group">
            <label for="id_doctor">Selecciona un doctor:</label>
            <select name="id_doctor" class="form-control" required>
                <?php foreach ($doctores as $doctor): ?>
                    <option value="<?php echo $doctor['id_doctor']; ?>"><?php echo $doctor['nombre_doctor']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="id_usuario">Selecciona un usuario:</label>
            <select name="id_usuario" class="form-control" required>
                <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?php echo $usuario['id']; ?>"><?php echo $usuario['nombre_usuario']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="fecha_cita">Fecha de la cita:</label>
            <input type="date" name="fecha_cita" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="hora_cita">Hora de la cita:</label>
            <input type="time" name="hora_cita" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción de la cita:</label>
            <textarea name="descripcion" class="form-control"></textarea>
        </div>
        <button type="submit" name="agendar_cita" class="btn btn-primary">Agendar Cita</button>
    </form>

    <!-- Mostrar disponibilidad de doctores -->
    <h2 class="mb-3">Disponibilidad de Doctores</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Doctor</th>
                <th>Día</th>
                <th>Hora Inicio</th>
                <th>Hora Fin</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql_disponibilidad = "SELECT d.nombre_doctor, h.dia_semana, h.hora_inicio, h.hora_fin, h.fecha_cita FROM horarios h
                                INNER JOIN doctores d ON h.id_doctor = d.id_doctor";
            $resultado_disponibilidad = mysqli_query($conexion, $sql_disponibilidad);
            $disponibilidad = mysqli_fetch_all($resultado_disponibilidad, MYSQLI_ASSOC);

            foreach ($disponibilidad as $disponible):
            ?>
                <tr>
                    <td><?php echo $disponible['nombre_doctor']; ?></td>
                    <td><?php echo $disponible['dia_semana']; ?></td>
                    <td><?php echo $disponible['hora_inicio']; ?></td>
                    <td><?php echo $disponible['hora_fin']; ?></td>
                    <td><?php echo $disponible['fecha_cita']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <!-- Mostrar citas programadas -->
    <h2 class="mb-3">Citas Programadas</h2>
    <div id="citas-programadas">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Doctor</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($citas as $cita): ?>
                    <tr>
                        <td><?php echo $cita['id']; ?></td>
                        <td><?php echo obtenerNombreUsuario($conexion, $cita['id_usuario']); ?></td>
                        <td><?php echo $cita['nombre_doctor']; ?></td>
                        <td><?php echo $cita['fecha_cita']; ?></td>
                        <td><?php echo $cita['hora_cita']; ?></td>
                        <td><?php echo $cita['descripcion']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


    <!-- Botón para imprimir reporte -->
    <button class="btn btn-success" onclick="imprimirReporte()">Imprimir Reporte</button>

    <!-- Botón para regresar a la página de login -->
    <a href="http://localhost:85/Policlinico/login.html" class="btn btn-danger">Regresar a Login</a>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
function imprimirReporte() {
    var contenidoCitas = document.getElementById('citas-programadas').outerHTML;
    var ventanaImpresion = window.open('', '_blank');
    ventanaImpresion.document.write('<html><head><title>Reporte de Citas</title></head><body>');
    ventanaImpresion.document.write('<h1>Reporte de Citas</h1>');
    ventanaImpresion.document.write(contenidoCitas);
    ventanaImpresion.document.write('</body></html>');
    ventanaImpresion.document.close();
    ventanaImpresion.print();
}
</script>


</body>
</html>

<?php
// Cerrar la conexión a la base de datos
mysqli_close($conexion);
?>

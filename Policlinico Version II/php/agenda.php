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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px;
            padding: 20px;
            background-color: #343a40;
            color: #fff;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }
        .nav-link {
            color: #fff;
        }
        .nav-link:hover {
            color: #f8f9fa;
        }
        .nav-link.active {
            font-weight: bold;
            background-color: #007bff;
        }
        .tab-pane {
            display: none;
        }
        .tab-pane.active {
            display: block;
        }
        .btn {
            margin-top: 10px;
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-success:hover {
            background-color: #218838;
            border-color: #218838;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #c82333;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2 class="mb-4">MENU</h2>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="#agendar-cita" data-toggle="tab"><i class="fas fa-calendar-plus mr-2"></i>Agendar Cita</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#disponibilidad-doctores" data-toggle="tab"><i class="fas fa-user-md mr-2"></i>Disponibilidad de Doctores</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#editar-cita" data-toggle="tab"><i class="fas fa-edit mr-2"></i>Editar Cita</a>
        </li>  
        <li class="nav-item">
            <a class="nav-link" href="#citas-programadas" data-toggle="tab"><i class="fas fa-calendar-alt mr-2"></i>Citas Programadas</a>
        </li>             
    </ul>
</div>

<div class="content">
    <div class="container mt-5">
        <h1 class="text-center mb-4">Agenda de Citas</h1>

        <!-- Contenido de las pestañas -->
        <div class="tab-content mt-3">
            <!-- Agendar Cita -->
            <div id="agendar-cita" class="tab-pane">
                <!-- Formulario para agendar una cita -->
                <h2 class="mb-3">Agendar Cita</h2>
                <form method="post" action="agenda.php">
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
            </div>

            <!-- Disponibilidad de Doctores -->
            <div id="disponibilidad-doctores" class="tab-pane">
                <!-- Aquí va tu tabla de disponibilidad de doctores -->
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
            </div>

            <!-- Editar Cita -->
                <div id="editar-cita" class="tab-pane fade">
                    <h2 class="mb-3">Editar Cita</h2>
                    <form method="post" action="editar_cita.php">
                        <div class="form-group">
                            <label for="id_cita">Selecciona la cita a editar:</label>
                            <select name="id_cita" class="form-control" required>
                                <?php foreach ($citas as $cita): ?>
                                    <option value="<?php echo $cita['id']; ?>"><?php echo obtenerNombreUsuario($conexion, $cita['id_usuario']) . ' - ' . $cita['fecha_cita'] . ' ' . $cita['hora_cita']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nueva_fecha_cita">Nueva fecha de la cita:</label>
                            <input type="date" name="nueva_fecha_cita" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="nueva_hora_cita">Nueva hora de la cita:</label>
                            <input type="time" name="nueva_hora_cita" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="nueva_descripcion">Nueva descripción de la cita:</label>
                            <textarea name="nueva_descripcion" class="form-control"></textarea>
                        </div>
                        <button type="submit" name="editar_cita" class="btn btn-primary">Editar Cita</button>
                    </form>
                </div>
                
                <!-- Citas Programadas -->
                <div id="citas-programadas" class="tab-pane">
                    <h1 class="text-center mb-4">Citas Programadas</h1>
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
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>

<script>
    // Función para mostrar la pestaña activa
    function mostrarPestanaActiva() {
        var pestanaActiva = $('.nav-link.active').attr('href');
        $(pestanaActiva).addClass('active');
    }

    // Mostrar la pestaña activa al cargar la página
    $(document).ready(function() {
        mostrarPestanaActiva();
    });

    // Cambiar la pestaña activa al hacer clic
    $('.nav-link').on('click', function() {
        $('.tab-pane').removeClass('active');
        mostrarPestanaActiva();
    });

</script>
<script>
    // Función para imprimir el reporte de citas
    function imprimirReporte() {
        var contenidoCitas = document.getElementById('citas-programadas').outerHTML;
        var ventanaImpresion = window.open('', '_blank');
        ventanaImpresion.document.write('<html><head><title>Reporte de Citas</title>');
        // Estilos
        ventanaImpresion.document.write('<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">');
        ventanaImpresion.document.write('<style>');
        ventanaImpresion.document.write('body { font-family: Arial, sans-serif; background-color: #f8f9fa; }');
        ventanaImpresion.document.write('.container { max-width: 800px; margin: 50px auto; padding: 30px; border-radius: 15px; box-shadow: 0px 0px 20px 0px rgba(0,0,0,0.1); }');
        ventanaImpresion.document.write('.table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }');
        ventanaImpresion.document.write('.table th, .table td { padding: 8px; border-bottom: 1px solid #dee2e6; }');
        ventanaImpresion.document.write('.table th { background-color: #007bff; color: #fff; }');
        ventanaImpresion.document.write('.btn { background-color: #28a745; color: #fff; border-color: #28a745; transition: background-color 0.3s; }');
        ventanaImpresion.document.write('.btn:hover { background-color: #218838; border-color: #1e7e34; }');
        ventanaImpresion.document.write('</style>');
        // Fin de estilos
        ventanaImpresion.document.write('</head><body>');
        ventanaImpresion.document.write('<div class="container">');
        ventanaImpresion.document.write('<h1 class="text-center mb-4">Reporte de Citas</h1>');
        ventanaImpresion.document.write(contenidoCitas);
        ventanaImpresion.document.write('<div class="text-center"><button class="btn" onclick="imprimirReporte()">Imprimir</button></div>');
        ventanaImpresion.document.write('</div>');
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

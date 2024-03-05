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

<!-- Menú en la barra lateral -->
<div class="sidebar">
    <h2 class="mb-4">MENU</h2>
    <ul class="nav flex-column">
        <!-- Opción para citas programadas -->
        <li class="nav-item">
            <a class="nav-link" href="#citas-programadas" data-toggle="tab"><i class="fas fa-calendar-alt mr-2"></i>Citas Programadas</a>
        </li>
        <!-- Nueva opción para receta médica -->
        <li class="nav-item">
            <a class="nav-link" href="#receta-medica" data-toggle="tab"><i class="fas fa-file-medical-alt mr-2"></i>Receta Médica</a>
        </li>
    </ul>
</div>

<!-- Contenido de la página -->
<div class="content">
    <div class="container mt-5">

        <!-- Contenido de las pestañas -->
        <div class="tab-content mt-3">
            <!-- Agendar Cita -->
            <div id="agendar-cita" class="tab-pane">
                <!-- Formulario para agendar una cita -->
                <!-- Aquí va tu formulario de agendar citas -->
            </div>
            <!-- Disponibilidad de Doctores -->
            <div id="disponibilidad-doctores" class="tab-pane">
                <!-- Aquí va tu tabla de disponibilidad de doctores -->
            </div>
            <!-- Editar Cita -->
            <div id="editar-cita" class="tab-pane">
                <!-- Aquí va tu formulario de edición de citas -->
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
                    <!-- Botón para imprimir reporte -->
                <button class="btn btn-success" onclick="imprimirReporte()">Imprimir Reporte</button>
                </div>
            <!-- Receta Médica -->
            <div id="receta-medica" class="tab-pane">
                <!-- Formulario para generar receta médica -->
                <h2 class="mb-3">Receta Médica</h2>
                <form id="form-receta" method="post" action="generar_receta.php">
                    <!-- Campos para receta médica -->
                    <div class="form-group">
                        <label for="id_usuario">Selecciona un usuario:</label>
                        <select name="id_usuario" class="form-control" required>
                            <?php foreach ($usuarios as $usuario): ?>
                                <option value="<?php echo $usuario['id']; ?>"><?php echo $usuario['nombre_usuario']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edad">Edad:</label>
                        <input type="number" class="form-control" id="edad" name="edad" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha">Fecha:</label>
                        <input type="date" class="form-control" id="fecha" name="fecha" required>
                    </div>
                    <div class="form-group">
                        <label for="diagnostico">Diagnóstico:</label>
                        <input type="text" class="form-control" id="diagnostico" name="diagnostico" required>
                    </div>
                    <div class="form-group">
                        <label for="descripcion">Descripción de la receta:</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-file-medical-alt mr-2"></i>Generar Receta</button>
                </form>
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
    // Función para imprimir el reporte de citas y redirigir a dashboard_doctor.php
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
        // Botón para regresar a dashboard_doctor.php
        ventanaImpresion.document.write('<div class="text-center"><a href="dashboard_doctor.php" class="btn btn-primary">Regresar a Dashboard</a></div>');
        ventanaImpresion.document.write('</div>');
        ventanaImpresion.document.write('</body></html>');
        ventanaImpresion.document.close();

        // Esperar a que la ventana de impresión se cargue completamente antes de intentar imprimir
        ventanaImpresion.onload = function() {
            ventanaImpresion.print();
        };
    }
</script>



</body>
</html>


<?php
// Cerrar la conexión a la base de datos
mysqli_close($conexion);
?>

<?php
// Recibiendo datos del formulario
$usuario = $_POST['id_usuario'];
$edad = $_POST['edad'];
$fecha = $_POST['fecha'];
$diagnostico = $_POST['diagnostico'];
$descripcion = $_POST['descripcion'];

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Receta Médica</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: Arial, sans-serif;
    }
    .container {
      max-width: 800px;
      margin: 50px auto;
      background-color: #fff;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0px 0px 20px 0px rgba(0,0,0,0.1);
      animation: fadeIn 1s ease-in-out;
    }
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    h1 {
      color: #007bff;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
      border-bottom: 2px solid #007bff;
      padding-bottom: 10px;
    }
    .patient-info {
      border: 2px solid #007bff;
      padding: 20px;
      border-radius: 15px;
      margin-bottom: 30px;
      animation: slideIn 1s ease-in-out;
    }
    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    .patient-info p {
      margin: 10px 0;
    }
    .btn-print {
      background-color: #28a745;
      color: #fff;
      border-color: #28a745;
      transition: background-color 0.3s;
      animation: pulse 1.5s infinite;
    }
    .btn-print:hover {
      background-color: #218838;
      border-color: #1e7e34;
      animation: none;
    }
    @keyframes pulse {
      0% {
        transform: scale(1);
      }
      50% {
        transform: scale(1.1);
      }
      100% {
        transform: scale(1);
      }
    }
  </style>
</head>
<body>

<div class="container">
  <h1 class="text-center mb-4">Receta Médica</h1>

  <div class="patient-info">
    <p><strong>Usuario:</strong> <?php echo $usuario; ?></p>
    <p><strong>Edad:</strong> <?php echo $edad; ?> años</p>
    <p><strong>Fecha:</strong> <?php echo $fecha; ?></p>
    <p><strong>Diagnóstico:</strong> <?php echo $diagnostico; ?></p>
  </div>

  <div class="row">
    <div class="col">
      <p class="mb-2"><strong>Descripción de la receta:</strong></p>
      <p class="mb-4"><?php echo $descripcion; ?></p>
    </div>
  </div>

  <!-- Botón para imprimir la receta -->
  <div class="text-center">
    <button class="btn btn-print mr-3" onclick="imprimirReceta()"><i class="fas fa-print mr-2"></i>Imprimir Receta</button>
    <!-- Botón para regresar a dashboard_doctor.php -->
    <a href="dashboard_doctor.php" class="btn btn-primary"><i class="fas fa-arrow-left mr-2"></i>Regresar</a>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // Función para imprimir la receta
    function imprimirReceta() {
        window.print();
    }
</script>

</body>
</html>



<?php
// Incluye la conexión a la base de datos
include '../config/database.php';

// Incluye el encabezado de la página
include '../includes/header.php';

// ===============================
// Registrar una nueva cita
// ===============================
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Obtener los datos enviados desde el formulario
    $mascota_id      = $_POST['mascota_id'];
    $veterinario_id  = $_POST['veterinario_id'];
    $fecha           = $_POST['fecha'];
    $hora            = $_POST['hora'];
    $motivo          = $_POST['motivo'];
    $estado          = $_POST['estado'];
    $observaciones   = $_POST['observaciones'];

    // Consulta SQL para insertar la cita en la base de datos
    $sql = "INSERT INTO citas
            (mascota_id, veterinario_id, fecha, hora, motivo, estado, observaciones)
            VALUES
            ('$mascota_id','$veterinario_id','$fecha','$hora','$motivo','$estado','$observaciones')";

    // Verifica si el registro fue exitoso
    if ($conexion->query($sql)) {

        // Mensaje de éxito
        echo "
        <div class='container mt-3'>
            <div class='alert alert-success alert-dismissible fade show'>
                ✅ Cita registrada correctamente
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
            </div>
        </div>
        ";

    } else {

        // Mensaje de error
        echo "
        <div class='container mt-3'>
            <div class='alert alert-danger alert-dismissible fade show'>
                ❌ Error: ".$conexion->error."
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
            </div>
        </div>
        ";
    }
}

// ===============================
// Obtener lista de mascotas
// ===============================
$mascotas = $conexion->query("
SELECT mascotas.id,
       mascotas.nombre,
       clientes.nombre AS dueño
FROM mascotas
INNER JOIN clientes
ON mascotas.cliente_id = clientes.id
");
// optimizar el codigo
// ===============================
// Obtener lista de veterinarios
// ===============================
$veterinarios = $conexion->query("
SELECT * FROM veterinarios
");
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <!-- Configuración del documento -->
    <meta charset="UTF-8">
    <title>Módulo de Citas</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>

        /* Fondo degradado */
        body{
            background:
            linear-gradient(135deg,#0f172a,#1e293b,#334155);
            min-height:100vh;
        }

        /* Tarjeta principal */
        .card-custom{
            border:none;
            border-radius:25px;
            backdrop-filter: blur(10px);
            background: rgba(255,255,255,0.96);
            box-shadow:0 10px 30px rgba(0,0,0,0.25);
        }

        /* Título principal */
        .title{
            font-weight:bold;
            color:#0f172a;
        }

        /* Subtítulo */
        .subtitle{
            color:#64748b;
        }

        /* Estilos para inputs y selects */
        .form-control,
        .form-select{
            border-radius:12px;
            padding:12px;
        }

        /* Botones personalizados */
        .btn-custom{
            border-radius:12px;
            padding:10px 25px;
            font-weight:600;
        }

        /* Caja del icono superior */
        .icon-box{
            width:70px;
            height:70px;
            border-radius:20px;
            background:#2563eb;
            display:flex;
            justify-content:center;
            align-items:center;
            color:white;
            font-size:30px;
            margin:auto;
            margin-bottom:15px;
            box-shadow:0 5px 15px rgba(37,99,235,0.4);
        }

    </style>

</head>

<body>

<!-- Contenedor principal -->
<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-9">

            <!-- Tarjeta del formulario -->
            <div class="card card-custom p-5">

                <!-- Icono del módulo -->
                <div class="icon-box">
                    <i class="bi bi-calendar2-heart"></i>
                </div>

                <!-- Encabezado -->
                <div class="text-center mb-4">

                    <h1 class="title">
                        Módulo de Citas Veterinarias
                    </h1>

                    <p class="subtitle">
                        Gestión moderna de consultas médicas y seguimiento clínico
                    </p>

                </div>

                <!-- Inicio del formulario -->
                <form method="POST">

                    <div class="row">

                        <!-- Selección de mascota -->
                        <div class="col-md-6 mb-4">

                            <label class="form-label fw-bold">
                                🐶 Mascota
                            </label>

                            <select name="mascota_id"
                                    class="form-select"
                                    required>

                                <option value="">
                                    Seleccionar mascota
                                </option>

                                <!-- Mostrar mascotas registradas -->
                                <?php while($m = $mascotas->fetch_assoc()) { ?>

                                    <option value="<?= $m['id'] ?>">

                                        <?= $m['nombre'] ?>
                                        - Dueño:
                                        <?= $m['dueño'] ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                        <!-- Selección del veterinario -->
                        <div class="col-md-6 mb-4">

                            <label class="form-label fw-bold">
                                👨‍⚕️ Veterinario
                            </label>

                            <select name="veterinario_id"
                                    class="form-select">

                                <option value="">
                                    Seleccionar veterinario
                                </option>

                                <!-- Mostrar veterinarios -->
                                <?php while($v = $veterinarios->fetch_assoc()) { ?>

                                    <option value="<?= $v['id'] ?>">
                                        <?= $v['nombre'] ?>
                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                        <!-- Fecha de la cita -->
                        <div class="col-md-6 mb-4">

                            <label class="form-label fw-bold">
                                📅 Fecha
                            </label>

                            <input type="date"
                                   name="fecha"
                                   class="form-control"
                                   required>

                        </div>

                        <!-- Hora de la cita -->
                        <div class="col-md-6 mb-4">

                            <label class="form-label fw-bold">
                                ⏰ Hora
                            </label>

                            <input type="time"
                                   name="hora"
                                   class="form-control"
                                   required>

                        </div>

                        <!-- Motivo de la consulta -->
                        <div class="col-md-12 mb-4">

                            <label class="form-label fw-bold">
                                🩺 Motivo
                            </label>

                            <input type="text"
                                   name="motivo"
                                   class="form-control"
                                   placeholder="Ej: Vacunación, cirugía, control..."
                                   required>

                        </div>

                        <!-- Estado de la cita -->
                        <div class="col-md-6 mb-4">

                            <label class="form-label fw-bold">
                                📌 Estado
                            </label>

                            <select name="estado"
                                    class="form-select">

                                <option value="Pendiente">
                                    Pendiente
                                </option>

                                <option value="Confirmada">
                                    Confirmada
                                </option>

                                <option value="Atendida">
                                    Atendida
                                </option>

                                <option value="Cancelada">
                                    Cancelada
                                </option>

                            </select>

                        </div>

                        <!-- Observaciones adicionales -->
                        <div class="col-md-6 mb-4">

                            <label class="form-label fw-bold">
                                📝 Observaciones
                            </label>

                            <textarea name="observaciones"
                                      class="form-control"
                                      rows="3"
                                      placeholder="Notas médicas..."></textarea>

                        </div>

                    </div>

                    <!-- Botones de acción -->
                    <div class="d-flex gap-3 justify-content-center flex-wrap mt-3">

                        <!-- Botón para registrar la cita -->
                        <button type="submit"
                                class="btn btn-primary btn-custom">

                            <i class="bi bi-calendar-check"></i>
                            Registrar Cita

                        </button>

                        <!-- Botón para ver el registro de citas -->
                        <a href="registro_citas.php"
                           class="btn btn-success btn-custom">

                            <i class="bi bi-card-list"></i>
                            Ver Registro

                        </a>

                        <!-- Botón para regresar al menú principal -->
                        <a href="../index.php"
                           class="btn btn-dark btn-custom">

                            <i class="bi bi-arrow-left"></i>
                            Volver al Menú

                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

<!-- JavaScript de Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
// Incluye el pie de página
include '../includes/footer.php';
?>
<?php
include '../config/database.php';
include '../includes/header.php';

// Registrar cita
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $mascota_id      = $_POST['mascota_id'];
    $veterinario_id  = $_POST['veterinario_id'];
    $fecha           = $_POST['fecha'];
    $hora            = $_POST['hora'];
    $motivo          = $_POST['motivo'];
    $estado          = $_POST['estado'];
    $observaciones   = $_POST['observaciones'];

    $sql = "INSERT INTO citas
            (mascota_id, veterinario_id, fecha, hora, motivo, estado, observaciones)
            VALUES
            ('$mascota_id','$veterinario_id','$fecha','$hora','$motivo','$estado','$observaciones')";

    if ($conexion->query($sql)) {

        echo "
        <div class='container mt-3'>
            <div class='alert alert-success alert-dismissible fade show'>
                ✅ Cita registrada correctamente
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
            </div>
        </div>
        ";

    } else {

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

// Mascotas
$mascotas = $conexion->query("
SELECT mascotas.id,
       mascotas.nombre,
       clientes.nombre AS dueño
FROM mascotas
INNER JOIN clientes
ON mascotas.cliente_id = clientes.id
");

// Veterinarios
$veterinarios = $conexion->query("
SELECT * FROM veterinarios
");
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <title>Módulo de Citas</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>

        body{
            background:
            linear-gradient(135deg,#0f172a,#1e293b,#334155);
            min-height:100vh;
        }

        .card-custom{
            border:none;
            border-radius:25px;
            backdrop-filter: blur(10px);
            background: rgba(255,255,255,0.96);
            box-shadow:0 10px 30px rgba(0,0,0,0.25);
        }

        .title{
            font-weight:bold;
            color:#0f172a;
        }

        .subtitle{
            color:#64748b;
        }

        .form-control,
        .form-select{
            border-radius:12px;
            padding:12px;
        }

        .btn-custom{
            border-radius:12px;
            padding:10px 25px;
            font-weight:600;
        }

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

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-9">

            <div class="card card-custom p-5">

                <!-- Icon -->
                <div class="icon-box">
                    <i class="bi bi-calendar2-heart"></i>
                </div>

                <!-- Título -->
                <div class="text-center mb-4">

                    <h1 class="title">
                        Módulo de Citas Veterinarias
                    </h1>

                    <p class="subtitle">
                        Gestión moderna de consultas médicas y seguimiento clínico
                    </p>

                </div>

                <form method="POST">

                    <div class="row">

                        <!-- Mascota -->
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

                                <?php while($m = $mascotas->fetch_assoc()) { ?>

                                    <option value="<?= $m['id'] ?>">

                                        <?= $m['nombre'] ?>
                                        - Dueño:
                                        <?= $m['dueño'] ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                        <!-- Veterinario -->
                        <div class="col-md-6 mb-4">

                            <label class="form-label fw-bold">
                                👨‍⚕️ Veterinario
                            </label>

                            <select name="veterinario_id"
                                    class="form-select">

                                <option value="">
                                    Seleccionar veterinario
                                </option>

                                <?php while($v = $veterinarios->fetch_assoc()) { ?>

                                    <option value="<?= $v['id'] ?>">
                                        <?= $v['nombre'] ?>
                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                        <!-- Fecha -->
                        <div class="col-md-6 mb-4">

                            <label class="form-label fw-bold">
                                📅 Fecha
                            </label>

                            <input type="date"
                                   name="fecha"
                                   class="form-control"
                                   required>

                        </div>

                        <!-- Hora -->
                        <div class="col-md-6 mb-4">

                            <label class="form-label fw-bold">
                                ⏰ Hora
                            </label>

                            <input type="time"
                                   name="hora"
                                   class="form-control"
                                   required>

                        </div>

                        <!-- Motivo -->
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

                        <!-- Estado -->
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

                        <!-- Observaciones -->
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

                    <!-- Botones -->
                  <div class="d-flex gap-3 justify-content-center flex-wrap mt-3">

    <!-- REGISTRAR -->
    <button type="submit"
            class="btn btn-primary btn-custom">

        <i class="bi bi-calendar-check"></i>
        Registrar Cita

    </button>

    <!-- VER REGISTRO -->
    <a href="registro_citas.php"
       class="btn btn-success btn-custom">

        <i class="bi bi-card-list"></i>
        Ver Registro

    </a>

    <!-- VOLVER -->
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php include '../includes/footer.php'; ?>
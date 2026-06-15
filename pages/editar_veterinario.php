<?php
include '../config/database.php';

// =========================
// VERIFICAR ID
// =========================
if (!isset($_GET['id'])) {

    die("ID no encontrado");
}

$id = $_GET['id'];

// =========================
// BUSCAR VETERINARIO
// =========================
$sql = "SELECT * FROM veterinarios WHERE id='$id'";

$resultado = $conexion->query($sql);

// Verificar existencia
if ($resultado->num_rows == 0) {

    die("Veterinario no encontrado");
}

$veterinario = $resultado->fetch_assoc();

// =========================
// ACTUALIZAR VETERINARIO
// =========================
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nombre       = $_POST['nombre'];
    $especialidad = $_POST['especialidad'];
    $telefono     = $_POST['telefono'];

    $update = "UPDATE veterinarios
               SET nombre='$nombre',
                   especialidad='$especialidad',
                   telefono='$telefono'
               WHERE id='$id'";

    if ($conexion->query($update)) {

        header("Location: veterinario.php?update=1");
        exit();

    } else {

        echo "Error al actualizar: " . $conexion->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <title>Editar Veterinario</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>

        body{
            background: linear-gradient(135deg,#0f172a,#1e293b,#334155);
            min-height:100vh;
        }

        .card-custom{
            border:none;
            border-radius:25px;
            box-shadow:0 10px 30px rgba(0,0,0,0.3);
        }

        .titulo{
            font-weight:bold;
            color:#0f172a;
        }

        .btn-custom{
            border-radius:12px;
            font-weight:600;
        }

        .icon-box{
            width:80px;
            height:80px;
            border-radius:20px;
            background:#f59e0b;
            color:white;
            display:flex;
            justify-content:center;
            align-items:center;
            font-size:35px;
            margin:auto;
            margin-bottom:20px;
            box-shadow:0 5px 15px rgba(245,158,11,0.4);
        }

    </style>

</head>

<body>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-7">

            <div class="card card-custom p-5">

                <!-- ICONO -->
                <div class="icon-box">
                    <i class="bi bi-pencil-square"></i>
                </div>

                <!-- TITULO -->
                <div class="text-center mb-4">

                    <h1 class="titulo">
                        ✏ Editar Veterinario
                    </h1>

                    <p class="text-muted">
                        Actualiza la información del veterinario
                    </p>

                </div>

                <!-- FORMULARIO -->
                <form method="POST">

                    <!-- NOMBRE -->
                    <div class="mb-4">

                        <label class="form-label fw-bold">
                            Nombre
                        </label>

                        <input type="text"
                               name="nombre"
                               class="form-control"
                               value="<?= $veterinario['nombre'] ?>"
                               required>

                    </div>

                    <!-- ESPECIALIDAD -->
                    <div class="mb-4">

                        <label class="form-label fw-bold">
                            Especialidad
                        </label>

                        <input type="text"
                               name="especialidad"
                               class="form-control"
                               value="<?= $veterinario['especialidad'] ?>"
                               required>

                    </div>

                    <!-- TELEFONO -->
                    <div class="mb-4">

                        <label class="form-label fw-bold">
                            Teléfono
                        </label>

                        <input type="text"
                               name="telefono"
                               class="form-control"
                               value="<?= $veterinario['telefono'] ?>"
                               required>

                    </div>

                    <!-- BOTONES -->
                    <div class="d-flex gap-3 justify-content-center mt-4">

                        <button type="submit"
                                class="btn btn-warning btn-custom">

                            <i class="bi bi-save"></i>
                            Actualizar

                        </button>

                        <a href="veterinario.php"
                           class="btn btn-dark btn-custom">

                            <i class="bi bi-arrow-left"></i>
                            Volver

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
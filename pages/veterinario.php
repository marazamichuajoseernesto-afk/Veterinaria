<?php
include '../config/database.php';

// =========================
// GUARDAR VETERINARIO
// =========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre       = $_POST['nombre'];
    $especialidad = $_POST['especialidad'];
    $telefono     = $_POST['telefono'];

    // CORREGIDO: veterinarios
    $sql = "INSERT INTO veterinarios(nombre, especialidad, telefono)
            VALUES ('$nombre', '$especialidad', '$telefono')";

    if ($conexion->query($sql)) {

        header('Location: veterinario.php?success=1');
        exit();

    } else {

        echo "Error: " . $conexion->error;
    }
}

// =========================
// ELIMINAR VETERINARIO
// =========================
if (isset($_GET['eliminar'])) {

    $id = $_GET['eliminar'];

    // CORREGIDO: veterinarios
    $conexion->query("DELETE FROM veterinarios WHERE id='$id'");

    header('Location: veterinario.php?delete=1');
    exit();
}

// =========================
// MOSTRAR VETERINARIOS
// =========================

// CORREGIDO: veterinarios
$resultado = $conexion->query("
SELECT * FROM veterinarios
ORDER BY id DESC
");

include '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <title>Módulo Veterinarios</title>

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

        .table{
            border-radius:15px;
            overflow:hidden;
        }

        .icon-box{
            width:70px;
            height:70px;
            border-radius:20px;
            background:#2563eb;
            color:white;
            display:flex;
            justify-content:center;
            align-items:center;
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

        <div class="col-lg-11">

            <div class="card card-custom p-5">

                <!-- ICONO -->
                <div class="icon-box">
                    <i class="bi bi-heart-pulse-fill"></i>
                </div>

                <!-- TITULO -->
                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>
                        <h1 class="titulo">
                            👨‍⚕️ Módulo Veterinarios
                        </h1>

                        <p class="text-muted">
                            Registro y gestión de veterinarios
                        </p>
                    </div>

                    <!-- VOLVER -->
                    <a href="../index.php"
                       class="btn btn-dark btn-custom">

                        <i class="bi bi-arrow-left"></i>
                        Volver al Menú

                    </a>

                </div>

                <!-- MENSAJES -->
                <?php if(isset($_GET['success'])): ?>

                    <div class="alert alert-success alert-dismissible fade show">

                        ✅ Veterinario registrado correctamente

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="alert"></button>

                    </div>

                <?php endif; ?>

                <?php if(isset($_GET['delete'])): ?>

                    <div class="alert alert-danger alert-dismissible fade show">

                        🗑 Veterinario eliminado correctamente

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="alert"></button>

                    </div>

                <?php endif; ?>

                <!-- FORMULARIO -->
                <form method="POST">

                    <div class="row">

                        <!-- NOMBRE -->
                        <div class="col-md-4 mb-3">

                            <label class="form-label fw-bold">
                                Nombre
                            </label>

                            <input type="text"
                                   name="nombre"
                                   class="form-control"
                                   placeholder="Ingrese nombre"
                                   required>

                        </div>

                        <!-- ESPECIALIDAD -->
                        <div class="col-md-4 mb-3">

                            <label class="form-label fw-bold">
                                Especialidad
                            </label>

                            <input type="text"
                                   name="especialidad"
                                   class="form-control"
                                   placeholder="Ej: Cirugía"
                                   required>

                        </div>

                        <!-- TELEFONO -->
                        <div class="col-md-4 mb-3">

                            <label class="form-label fw-bold">
                                Teléfono
                            </label>

                            <input type="text"
                                   name="telefono"
                                   class="form-control"
                                   placeholder="Ingrese teléfono"
                                   required>

                        </div>

                    </div>

                    <button type="submit"
                            class="btn btn-primary btn-custom mt-2">

                        <i class="bi bi-save"></i>
                        Guardar Veterinario

                    </button>

                </form>

                <hr class="my-4">

                <!-- TABLA -->
                <div class="table-responsive">

                    <table class="table table-hover table-bordered align-middle text-center">

                        <thead class="table-dark">

                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Especialidad</th>
                                <th>Teléfono</th>
                                <th>Acciones</th>
                            </tr>

                        </thead>

                        <tbody>

                        <?php while($fila = $resultado->fetch_assoc()): ?>

                            <tr>

                                <td><?= $fila['id'] ?></td>

                                <td><?= $fila['nombre'] ?></td>

                                <td><?= $fila['especialidad'] ?></td>

                                <td><?= $fila['telefono'] ?></td>

                                <td>

                                    <!-- EDITAR -->
                                    <a href="editar_veterinario.php?id=<?= $fila['id'] ?>"
                                       class="btn btn-warning btn-sm btn-custom">

                                        ✏ Editar

                                    </a>

                                    <!-- ELIMINAR -->
                                    <a href="veterinario.php?eliminar=<?= $fila['id'] ?>"
                                       class="btn btn-danger btn-sm btn-custom"
                                       onclick="return confirm('¿Desea eliminar este veterinario?')">

                                       🗑 Eliminar

                                    </a>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php include '../includes/footer.php'; ?>
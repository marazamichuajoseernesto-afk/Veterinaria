<?php
include '../config/database.php';

// =========================
// REGISTRAR MASCOTA
// =========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $cliente_id = $_POST['cliente_id'];
    $nombre = $_POST['nombre'];
    $especie = $_POST['especie'];
    $raza = $_POST['raza'];
    $edad = $_POST['edad'];

    $conexion->query("
        INSERT INTO mascotas (cliente_id, nombre, especie, raza, edad)
        VALUES ('$cliente_id', '$nombre', '$especie', '$raza', '$edad')
    ");

    header('Location: mascotas.php?success=1');
    exit();
}

// =========================
// ELIMINAR MASCOTA
// =========================
if (isset($_GET['eliminar'])) {

    $id = $_GET['eliminar'];

    $conexion->query("DELETE FROM mascotas WHERE id=$id");

    header('Location: mascotas.php?delete=1');
    exit();
}

// =========================
// CONSULTAS
// =========================
$clientes = $conexion->query("
    SELECT * FROM clientes ORDER BY nombre
");

$mascotas = $conexion->query("
    SELECT mascotas.*, clientes.nombre AS propietario
    FROM mascotas
    INNER JOIN clientes ON mascotas.cliente_id = clientes.id
    ORDER BY mascotas.id DESC
");

include '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">

<title>Módulo Mascotas</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- ICONOS -->
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

body{
    background: linear-gradient(to right,#0f172a,#1e293b);
    font-family: Arial, Helvetica, sans-serif;
}

.card{
    border:none;
    border-radius:20px;
    box-shadow:0px 5px 20px rgba(0,0,0,0.3);
}

.titulo{
    color:#0f172a;
    font-weight:bold;
}

.btn-custom{
    border-radius:10px;
}

.table{
    border-radius:10px;
    overflow:hidden;
}

label{
    font-weight:bold;
}

</style>

</head>

<body>

<div class="container mt-5">

    <div class="card p-4">

        <!-- TITULO -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <h2 class="titulo">
                <i class="fa-solid fa-paw"></i>
                Registro de Mascotas
            </h2>

            <!-- BOTON MENU -->
            <a href="../index.php"
               class="btn btn-secondary btn-custom">

               <i class="fa-solid fa-arrow-left"></i>
               Volver al Menú

            </a>

        </div>

        <!-- ALERTAS -->
        <?php if(isset($_GET['success'])): ?>

            <div class="alert alert-success alert-dismissible fade show">

                Mascota registrada correctamente.

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        <?php endif; ?>

        <?php if(isset($_GET['delete'])): ?>

            <div class="alert alert-danger alert-dismissible fade show">

                Mascota eliminada correctamente.

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        <?php endif; ?>

        <!-- FORMULARIO -->
        <form method="POST">

            <div class="row">

                <!-- CLIENTE -->
                <div class="col-md-4 mb-3">

                    <label>Propietario</label>

                    <select name="cliente_id"
                            class="form-select"
                            required>

                        <option value="">
                            Seleccione propietario
                        </option>

                        <?php while($cliente = $clientes->fetch_assoc()): ?>

                            <option value="<?= $cliente['id'] ?>">

                                <?= $cliente['nombre'] ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>

                <!-- NOMBRE -->
                <div class="col-md-4 mb-3">

                    <label>Nombre Mascota</label>

                    <input type="text"
                           name="nombre"
                           class="form-control"
                           placeholder="Ingrese nombre"
                           required>

                </div>

                <!-- ESPECIE -->
                <div class="col-md-4 mb-3">

                    <label>Especie</label>

                    <input type="text"
                           name="especie"
                           class="form-control"
                           placeholder="Ej: Perro, Gato"
                           required>

                </div>

                <!-- RAZA -->
                <div class="col-md-6 mb-3">

                    <label>Raza</label>

                    <input type="text"
                           name="raza"
                           class="form-control"
                           placeholder="Ingrese raza"
                           required>

                </div>

                <!-- EDAD -->
                <div class="col-md-6 mb-3">

                    <label>Edad</label>

                    <input type="number"
                           name="edad"
                           class="form-control"
                           placeholder="Edad"
                           required>

                </div>

            </div>

            <!-- BOTON GUARDAR -->
            <button type="submit"
                    class="btn btn-primary btn-custom">

                <i class="fa-solid fa-floppy-disk"></i>
                Registrar Mascota

            </button>

        </form>

        <hr>

        <!-- TABLA -->
        <div class="table-responsive">

            <table class="table table-hover table-bordered align-middle text-center">

                <thead class="table-dark">

                    <tr>
                        <th>ID</th>
                        <th>Mascota</th>
                        <th>Propietario</th>
                        <th>Especie</th>
                        <th>Raza</th>
                        <th>Edad</th>
                        <th>Acciones</th>
                    </tr>

                </thead>

                <tbody>

                <?php while($fila = $mascotas->fetch_assoc()): ?>

                    <tr>

                        <td><?= $fila['id'] ?></td>

                        <td>
                            <?= $fila['nombre'] ?>
                        </td>

                        <td>
                            <?= $fila['propietario'] ?>
                        </td>

                        <td>
                            <?= $fila['especie'] ?>
                        </td>

                        <td>
                            <?= $fila['raza'] ?>
                        </td>

                        <td>
                            <?= $fila['edad'] ?> años
                        </td>

                        <td>

                            <!-- BOTON EDITAR -->
                            <a href="editar_mascota.php?id=<?= $fila['id'] ?>"
                               class="btn btn-warning btn-sm btn-custom">

                               <i class="fa-solid fa-pen"></i>
                               Editar

                            </a>

                            <!-- BOTON ELIMINAR -->
                            <a href="mascotas.php?eliminar=<?= $fila['id'] ?>"
                               class="btn btn-danger btn-sm btn-custom"
                               onclick="return confirm('¿Desea eliminar esta mascota?')">

                               <i class="fa-solid fa-trash"></i>
                               Eliminar

                            </a>

                        </td>

                    </tr>

                <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php include '../includes/footer.php'; ?>
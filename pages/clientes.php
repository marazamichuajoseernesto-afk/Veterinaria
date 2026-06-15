<?php
include '../config/database.php';

// =========================
// GUARDAR CLIENTE
// =========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    $conexion->query("INSERT INTO clientes (nombre, telefono, direccion)
                      VALUES ('$nombre', '$telefono', '$direccion')");

    header('Location: clientes.php?success=1');
    exit();
}

// =========================
// ELIMINAR CLIENTE
// =========================
if (isset($_GET['eliminar'])) {

    $id = $_GET['eliminar'];

    $conexion->query("DELETE FROM clientes WHERE id=$id");

    header('Location: clientes.php?delete=1');
    exit();
}

// =========================
// MOSTRAR CLIENTES
// =========================
$resultado = $conexion->query("SELECT * FROM clientes ORDER BY id DESC");

include '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Módulo Clientes</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

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
            font-weight:bold;
            color:#0f172a;
        }

        .btn-custom{
            border-radius:10px;
        }

        .table{
            border-radius:10px;
            overflow:hidden;
        }

    </style>
</head>

<body>

<div class="container mt-5">

    <div class="card p-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="titulo">🐾 Módulo de Clientes</h2>

            <!-- BOTON VOLVER -->
            <a href="../index.php" class="btn btn-secondary btn-custom">
                ← Volver al Menú
            </a>
        </div>

        <!-- MENSAJES -->
        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success">
                Cliente guardado correctamente.
            </div>
        <?php endif; ?>

        <?php if(isset($_GET['delete'])): ?>
            <div class="alert alert-danger">
                Cliente eliminado correctamente.
            </div>
        <?php endif; ?>

        <!-- FORMULARIO -->
        <form method="POST">

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label>Nombre</label>
                    <input type="text" name="nombre" class="form-control" placeholder="Ingrese nombre" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" class="form-control" placeholder="Ingrese teléfono" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Dirección</label>
                    <input type="text" name="direccion" class="form-control" placeholder="Ingrese dirección" required>
                </div>

            </div>

            <button type="submit" class="btn btn-primary btn-custom">
                💾 Guardar Cliente
            </button>

        </form>

        <hr>

        <!-- TABLA -->
        <div class="table-responsive">

            <table class="table table-hover table-bordered align-middle text-center">

                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>

                <?php while($fila = $resultado->fetch_assoc()): ?>

                    <tr>

                        <td><?= $fila['id'] ?></td>
                        <td><?= $fila['nombre'] ?></td>
                        <td><?= $fila['telefono'] ?></td>
                        <td><?= $fila['direccion'] ?></td>

                        <td>

                            <!-- BOTON EDITAR -->
                            <a href="editar_cliente.php?id=<?= $fila['id'] ?>"
                               class="btn btn-warning btn-sm btn-custom">
                               ✏ Editar
                            </a>

                            <!-- BOTON ELIMINAR -->
                            <a href="clientes.php?eliminar=<?= $fila['id'] ?>"
                               class="btn btn-danger btn-sm btn-custom"
                               onclick="return confirm('¿Desea eliminar este cliente?')">
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

</body>
</html>

<?php include '../includes/footer.php'; ?>
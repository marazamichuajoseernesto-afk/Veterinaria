<?php
include '../config/database.php';

$id = $_GET['id'];

$resultado = $conexion->query("SELECT * FROM clientes WHERE id=$id");
$cliente = $resultado->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    $conexion->query("UPDATE clientes 
                      SET nombre='$nombre',
                          telefono='$telefono',
                          direccion='$direccion'
                      WHERE id=$id");

    header("Location: clientes.php?update=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Cliente</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background: linear-gradient(to right,#0f172a,#1e293b);
        }

        .card{
            border-radius:20px;
            border:none;
        }
    </style>

</head>
<body>

<div class="container mt-5">

    <div class="card p-4">

        <h2 class="mb-4">✏ Editar Cliente</h2>

        <form method="POST">

            <div class="mb-3">
                <label>Nombre</label>
                <input type="text"
                       name="nombre"
                       class="form-control"
                       value="<?= $cliente['nombre'] ?>"
                       required>
            </div>

            <div class="mb-3">
                <label>Teléfono</label>
                <input type="text"
                       name="telefono"
                       class="form-control"
                       value="<?= $cliente['telefono'] ?>"
                       required>
            </div>

            <div class="mb-3">
                <label>Dirección</label>
                <input type="text"
                       name="direccion"
                       class="form-control"
                       value="<?= $cliente['direccion'] ?>"
                       required>
            </div>

            <button type="submit" class="btn btn-primary">
                💾 Actualizar
            </button>

            <a href="clientes.php" class="btn btn-dark btn-custom">
                ← Volver
            </a>

        </form>

    </div>

</div>

</body>
</html>
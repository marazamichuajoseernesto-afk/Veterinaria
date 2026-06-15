<?php
include '../config/database.php';

// Verificar si llega el ID
if (!isset($_GET['id'])) {
    die("ID no encontrado");
}

$id = $_GET['id'];

// Obtener datos de la mascota
$sql = "SELECT * FROM mascotas WHERE id = $id";
$resultado = $conexion->query($sql);

if ($resultado->num_rows == 0) {
    die("Mascota no encontrada");
}

$mascota = $resultado->fetch_assoc();

// Actualizar datos
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $cliente_id = $_POST['cliente_id'];
    $nombre = $_POST['nombre'];
    $especie = $_POST['especie'];
    $raza = $_POST['raza'];

    $update = "UPDATE mascotas 
               SET cliente_id='$cliente_id',
                   nombre='$nombre',
                   especie='$especie',
                   raza='$raza'
               WHERE id=$id";

    if ($conexion->query($update)) {
        header("Location: mascotas.php");
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
    <title>Editar Mascota</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="card shadow p-4">
        <h2 class="mb-4 text-primary">Editar Mascota</h2>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Cliente ID</label>
                <input type="number" name="cliente_id" class="form-control"
                       value="<?php echo $mascota['cliente_id']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-control"
                       value="<?php echo $mascota['nombre']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Especie</label>
                <input type="text" name="especie" class="form-control"
                       value="<?php echo $mascota['especie']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Raza</label>
                <input type="text" name="raza" class="form-control"
                       value="<?php echo $mascota['raza']; ?>" required>
            </div>

            <button type="submit" class="btn btn-success">
                Actualizar
            </button>

            <a href="mascotas.php" class="btn btn-secondary">
                Volver
            </a>

        </form>
    </div>

</div>

</body>
</html>
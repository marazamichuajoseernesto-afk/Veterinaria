<?php
include '../config/database.php';
include '../includes/header.php';

// =========================
// MOSTRAR HISTORIALES
// =========================
$resultado = $conexion->query("
SELECT
    historiales_medicos.id,
    mascotas.nombre AS mascota,
    clientes.nombre AS dueño,
    historiales_medicos.fecha,
    historiales_medicos.motivo,
    historiales_medicos.diagnostico,
    historiales_medicos.tratamiento,
    historiales_medicos.alergias,
    historiales_medicos.vacunas,
    historiales_medicos.peso

FROM historiales_medicos

INNER JOIN mascotas
ON historiales_medicos.mascota_id = mascotas.id

INNER JOIN clientes
ON mascotas.cliente_id = clientes.id

ORDER BY historiales_medicos.id DESC
");
?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<title>Registro Historial Clínico</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

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
    background:white;
    box-shadow:0 10px 30px rgba(0,0,0,0.25);
}

.titulo{
    font-weight:bold;
    color:#0f172a;
}

.table{
    border-radius:15px;
    overflow:hidden;
}

.btn-custom{
    border-radius:12px;
    font-weight:600;
}

</style>

</head>

<body>

<div class="container py-5">

<div class="card card-custom p-5">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h1 class="titulo">
📋 Registro Historial Clínico
</h1>

<p class="text-muted">
Historial médico completo de mascotas
</p>

</div>

<div class="d-flex gap-2">

<a href="historial_medico.php"
class="btn btn-primary btn-custom">

<i class="bi bi-plus-circle"></i>
Nuevo Historial

</a>

<a href="../index.php"
class="btn btn-dark btn-custom">

<i class="bi bi-arrow-left"></i>
Menú

</a>

</div>

</div>

<div class="table-responsive">

<table class="table table-hover table-bordered align-middle text-center">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Mascota</th>
<th>Dueño</th>
<th>Fecha</th>
<th>Motivo</th>
<th>Diagnóstico</th>
<th>Tratamiento</th>
<th>Alergias</th>
<th>Vacunas</th>
<th>Peso</th>

</tr>

</thead>

<tbody>

<?php while($fila = $resultado->fetch_assoc()): ?>

<tr>

<td><?= $fila['id'] ?></td>

<td>
🐶 <?= $fila['mascota'] ?>
</td>

<td>
👤 <?= $fila['dueño'] ?>
</td>

<td>
<?= $fila['fecha'] ?>
</td>

<td>
<?= $fila['motivo'] ?>
</td>

<td>
<?= $fila['diagnostico'] ?>
</td>

<td>
<?= $fila['tratamiento'] ?>
</td>

<td>
<?= $fila['alergias'] ?>
</td>

<td>
<?= $fila['vacunas'] ?>
</td>

<td>
⚖️ <?= $fila['peso'] ?> kg
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
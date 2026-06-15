<?php
include '../config/database.php';
include '../includes/header.php';

// =========================
// GUARDAR PRODUCTO
// =========================
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $producto           = $_POST['producto'];
    $categoria          = $_POST['categoria'];
    $cantidad           = $_POST['cantidad'];
    $stock_minimo       = $_POST['stock_minimo'];
    $precio_compra      = $_POST['precio_compra'];
    $precio_venta       = $_POST['precio_venta'];
    $fecha_vencimiento  = !empty($_POST['fecha_vencimiento'])
                          ? "'".$_POST['fecha_vencimiento']."'"
                          : "NULL";

    $sql = "INSERT INTO inventario
            (producto, categoria, cantidad, stock_minimo,
             precio_compra, precio_venta, fecha_vencimiento)

            VALUES

            ('$producto', '$categoria', '$cantidad',
             '$stock_minimo', '$precio_compra',
             '$precio_venta', $fecha_vencimiento)";

    if ($conexion->query($sql)) {

        echo "
        <div class='container mt-3'>
            <div class='alert alert-success alert-dismissible fade show'>
                ✅ Producto registrado correctamente
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

// =========================
// MOSTRAR INVENTARIO
// =========================
$resultado = $conexion->query("
SELECT * FROM inventario
ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<title>Módulo Inventario</title>

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
    background:rgba(255,255,255,0.97);
    box-shadow:0 10px 30px rgba(0,0,0,0.25);
}

.titulo{
    font-weight:bold;
    color:#0f172a;
}

.subtitulo{
    color:#64748b;
}

.form-control{
    border-radius:12px;
    padding:12px;
}

.btn-custom{
    border-radius:12px;
    font-weight:600;
    padding:10px 20px;
}

.table{
    border-radius:15px;
    overflow:hidden;
}

.icon-box{
    width:80px;
    height:80px;
    border-radius:20px;
    background:#16a34a;
    color:white;
    display:flex;
    justify-content:center;
    align-items:center;
    font-size:35px;
    margin:auto;
    margin-bottom:20px;
    box-shadow:0 5px 15px rgba(22,163,74,0.4);
}

.stock-bajo{
    background:#fee2e2 !important;
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
    <i class="bi bi-box-seam-fill"></i>
</div>

<!-- TITULO -->
<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h1 class="titulo">
📦 Módulo Inventario
</h1>

<p class="subtitulo">
Control moderno de productos y stock veterinario
</p>

</div>

<a href="../index.php"
class="btn btn-dark btn-custom">

<i class="bi bi-arrow-left"></i>
Volver al Menú

</a>

</div>

<!-- FORMULARIO -->
<form method="POST">

<div class="row">

<!-- PRODUCTO -->
<div class="col-md-4 mb-4">

<label class="form-label fw-bold">
📦 Producto
</label>

<input type="text"
name="producto"
class="form-control"
placeholder="Ingrese producto"
required>

</div>

<!-- CATEGORIA -->
<div class="col-md-4 mb-4">

<label class="form-label fw-bold">
🏷 Categoría
</label>

<input type="text"
name="categoria"
class="form-control"
placeholder="Medicamento, alimento..."
required>

</div>

<!-- CANTIDAD -->
<div class="col-md-4 mb-4">

<label class="form-label fw-bold">
🔢 Cantidad
</label>

<input type="number"
name="cantidad"
class="form-control"
required>

</div>

<!-- STOCK MINIMO -->
<div class="col-md-4 mb-4">

<label class="form-label fw-bold">
⚠️ Stock Mínimo
</label>

<input type="number"
name="stock_minimo"
class="form-control"
value="5"
required>

</div>

<!-- PRECIO COMPRA -->
<div class="col-md-4 mb-4">

<label class="form-label fw-bold">
💲 Precio Compra
</label>

<input type="number"
step="0.01"
name="precio_compra"
class="form-control"
required>

</div>

<!-- PRECIO VENTA -->
<div class="col-md-4 mb-4">

<label class="form-label fw-bold">
💰 Precio Venta
</label>

<input type="number"
step="0.01"
name="precio_venta"
class="form-control"
required>

</div>

<!-- FECHA -->
<div class="col-md-4 mb-4">

<label class="form-label fw-bold">
📅 Fecha Vencimiento
</label>

<input type="date"
name="fecha_vencimiento"
class="form-control">

</div>

</div>

<!-- BOTONES -->
<div class="d-flex gap-3 justify-content-center flex-wrap mt-3">

<button type="submit"
class="btn btn-success btn-custom">

<i class="bi bi-save"></i>
Guardar Producto

</button>

<a href="../index.php"
class="btn btn-dark btn-custom">

<i class="bi bi-arrow-left"></i>
Volver

</a>

</div>

</form>

<hr class="my-4">

<!-- TABLA -->
<div class="table-responsive">

<table class="table table-hover table-bordered align-middle text-center">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Producto</th>
<th>Categoría</th>
<th>Cantidad</th>
<th>Stock Mínimo</th>
<th>Compra</th>
<th>Venta</th>
<th>Vencimiento</th>
<th>Estado</th>

</tr>

</thead>

<tbody>

<?php while($fila = $resultado->fetch_assoc()): ?>

<tr class="<?= ($fila['cantidad'] <= $fila['stock_minimo']) ? 'stock-bajo' : '' ?>">

<td><?= $fila['id'] ?></td>

<td><?= $fila['producto'] ?></td>

<td><?= $fila['categoria'] ?></td>

<td><?= $fila['cantidad'] ?></td>

<td><?= $fila['stock_minimo'] ?></td>

<td>Bs <?= $fila['precio_compra'] ?></td>

<td>Bs <?= $fila['precio_venta'] ?></td>

<td>

<?php
if($fila['fecha_vencimiento']){
    echo $fila['fecha_vencimiento'];
}else{
    echo "Sin fecha";
}
?>

</td>

<td>

<?php
if($fila['cantidad'] <= $fila['stock_minimo']){

    echo "<span class='badge bg-danger'>
            Stock Bajo
          </span>";

}else{

    echo "<span class='badge bg-success'>
            Disponible
          </span>";
}
?>

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
<?php
include '../config/database.php';
include '../includes/header.php';

// ==================================================
// VER FACTURA (VISTA HTML)
// ==================================================
if (isset($_GET['action']) && $_GET['action'] == 'view' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = $conexion->prepare("
        SELECT facturas.*, clientes.nombre AS cliente_nombre, clientes.email, clientes.telefono,
               mascotas.nombre AS mascota_nombre
        FROM facturas
        INNER JOIN clientes ON facturas.cliente_id = clientes.id
        LEFT JOIN mascotas ON facturas.mascota_id = mascotas.id
        WHERE facturas.id = ?
    ");
    $query->bind_param("i", $id);
    $query->execute();
    $factura = $query->get_result()->fetch_assoc();

    if (!$factura) {
        die("Factura no encontrada");
    }
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Factura #<?= $factura['id'] ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <style>
            body{ background: #f2f2f2; padding: 40px 20px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
            .factura-container { max-width: 800px; margin: 0 auto; background: white; border-radius: 20px; padding: 30px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
            .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #f59e0b; padding-bottom: 20px; }
            .factura-title { font-size: 32px; font-weight: bold; color: #f59e0b; }
            .datos-cliente { background: #f8f9fa; padding: 15px; border-radius: 12px; margin: 20px 0; line-height: 1.6; }
            .tabla-items { width: 100%; border-collapse: collapse; margin: 20px 0; }
            .tabla-items th, .tabla-items td { border: 1px solid #ddd; padding: 12px; text-align: left; }
            .tabla-items th { background-color: #f59e0b; color: white; }
            .totales { text-align: right; margin-top: 20px; font-size: 1.1em; }
            .footer { text-align: center; margin-top: 40px; font-size: 12px; color: #777; border-top: 1px solid #ddd; padding-top: 20px; }
            .btn-volver { display: inline-block; margin-top: 20px; background: #6c757d; color: white; padding: 10px 20px; border-radius: 30px; text-decoration: none; }
            .btn-volver:hover { background: #5a6268; color: white; }
        </style>
    </head>
    <body>
        <div class="factura-container">
            <div class="header">
                <div class="factura-title">🐾 CLÍNICA VETERINARIA</div>
                <p>Av. Principal #123 - Tel: 555-1234</p>
                <h2>FACTURA Nº <?= $factura['id'] ?></h2>
                <p><strong>Fecha:</strong> <?= $factura['fecha'] ?></p>
            </div>

            <div class="datos-cliente">
                <strong>👤 Cliente:</strong> <?= htmlspecialchars($factura['cliente_nombre']) ?><br>
                <strong>📧 Email:</strong> <?= htmlspecialchars($factura['email']) ?><br>
                <strong>📞 Teléfono:</strong> <?= htmlspecialchars($factura['telefono']) ?><br>
                <strong>🐶 Mascota:</strong> <?= $factura['mascota_nombre'] ? htmlspecialchars($factura['mascota_nombre']) : 'Sin mascota' ?>
            </div>

            <table class="tabla-items">
                <thead>
                    <tr><th>Concepto</th><th>Monto (Bs)</th></tr>
                </thead>
                <tbody>
                    <tr><td>Subtotal</td><td><?= number_format($factura['subtotal'], 2) ?></td></tr>
                    <tr><td>Descuento</td><td><?= number_format($factura['descuento'], 2) ?></td></tr>
                    <tr><td>Impuesto</td><td><?= number_format($factura['impuesto'], 2) ?></td></tr>
                    <tr style="font-weight:bold; background:#f2f2f2;"><td>TOTAL</td><td><?= number_format($factura['total'], 2) ?> Bs</td></tr>
                </tbody>
            </table>

            <div class="totales">
                <p><strong>Estado:</strong> <?= $factura['estado'] ?></p>
            </div>
            <div class="footer">
                ¡Gracias por confiar en nosotros! | Factura electrónica
            </div>
            <div style="text-align: center;">
                <a href="facturacion.php" class="btn-volver"><i class="bi bi-arrow-left"></i> Volver al listado</a>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
    exit;
}

// ==================================================
// ELIMINAR FACTURA
// ==================================================
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conexion->prepare("DELETE FROM facturas WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: facturacion.php?msg=deleted");
    } else {
        header("Location: facturacion.php?msg=error");
    }
    exit;
}

// ==================================================
// EDITAR FACTURA (mostrar formulario precargado)
// ==================================================
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = $conexion->prepare("SELECT * FROM facturas WHERE id = ?");
    $query->bind_param("i", $id);
    $query->execute();
    $factura_edit = $query->get_result()->fetch_assoc();
    if (!$factura_edit) {
        die("Factura no encontrada");
    }
    // Obtener listas para selects
    $clientes = $conexion->query("SELECT * FROM clientes ORDER BY nombre ASC");
    $mascotas = $conexion->query("
        SELECT mascotas.id, mascotas.nombre, clientes.nombre AS dueño 
        FROM mascotas 
        INNER JOIN clientes ON mascotas.cliente_id = clientes.id
    ");
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Editar Factura</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <style>
            body{ background: linear-gradient(135deg,#0f172a,#1e293b,#334155); min-height:100vh; }
            .card-custom{ border:none; border-radius:25px; background:rgba(255,255,255,0.97); box-shadow:0 10px 30px rgba(0,0,0,0.25); }
            .btn-custom{ border-radius:12px; font-weight:600; padding:10px 20px; }
        </style>
    </head>
    <body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card card-custom p-5">
                    <h2 class="text-center mb-4">✏️ Editar Factura #<?= $id ?></h2>
                    <form method="POST" action="facturacion.php?action=update&id=<?= $id ?>">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">👤 Cliente</label>
                                <select name="cliente_id" class="form-select" required>
                                    <option value="">Seleccionar cliente</option>
                                    <?php while($c = $clientes->fetch_assoc()): ?>
                                        <option value="<?= $c['id'] ?>" <?= $c['id'] == $factura_edit['cliente_id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['nombre']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">🐶 Mascota</label>
                                <select name="mascota_id" class="form-select">
                                    <option value="">Sin mascota</option>
                                    <?php while($m = $mascotas->fetch_assoc()): ?>
                                        <option value="<?= $m['id'] ?>" <?= $m['id'] == $factura_edit['mascota_id'] ? 'selected' : '' ?>><?= htmlspecialchars($m['nombre']) ?> - Dueño: <?= htmlspecialchars($m['dueño']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">💰 Subtotal</label>
                                <input type="number" step="0.01" name="subtotal" class="form-control" value="<?= $factura_edit['subtotal'] ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">🏷 Descuento</label>
                                <input type="number" step="0.01" name="descuento" class="form-control" value="<?= $factura_edit['descuento'] ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">📈 Impuesto</label>
                                <input type="number" step="0.01" name="impuesto" class="form-control" value="<?= $factura_edit['impuesto'] ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">📌 Estado</label>
                                <select name="estado" class="form-select">
                                    <option value="Pendiente" <?= $factura_edit['estado'] == 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                    <option value="Pagada" <?= $factura_edit['estado'] == 'Pagada' ? 'selected' : '' ?>>Pagada</option>
                                    <option value="Parcial" <?= $factura_edit['estado'] == 'Parcial' ? 'selected' : '' ?>>Parcial</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <a href="facturacion.php" class="btn btn-secondary btn-custom">Cancelar</a>
                            <button type="submit" class="btn btn-warning btn-custom">Actualizar Factura</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
    exit;
}

// ==================================================
// ACTUALIZAR FACTURA (POST)
// ==================================================
if (isset($_GET['action']) && $_GET['action'] == 'update' && isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_GET['id']);
    $cliente_id = $_POST['cliente_id'];
    $mascota_id = !empty($_POST['mascota_id']) ? $_POST['mascota_id'] : null;
    $subtotal = $_POST['subtotal'];
    $descuento = $_POST['descuento'];
    $impuesto = $_POST['impuesto'];
    $total = ($subtotal - $descuento) + $impuesto;
    $estado = $_POST['estado'];

    $stmt = $conexion->prepare("UPDATE facturas SET cliente_id=?, mascota_id=?, subtotal=?, descuento=?, impuesto=?, total=?, estado=? WHERE id=?");
    $stmt->bind_param("iiddddsi", $cliente_id, $mascota_id, $subtotal, $descuento, $impuesto, $total, $estado, $id);
    if ($stmt->execute()) {
        header("Location: facturacion.php?msg=updated");
    } else {
        header("Location: facturacion.php?msg=error");
    }
    exit;
}

// ==================================================
// CREAR FACTURA (POST)
// ==================================================
$ultima_factura_id = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_GET['action'])) {
    $cliente_id = $_POST['cliente_id'];
    $mascota_id = !empty($_POST['mascota_id']) ? $_POST['mascota_id'] : null;
    $subtotal = $_POST['subtotal'];
    $descuento = $_POST['descuento'];
    $impuesto = $_POST['impuesto'];
    $total = ($subtotal - $descuento) + $impuesto;
    $estado = $_POST['estado'];

    $sql = "INSERT INTO facturas (cliente_id, mascota_id, subtotal, descuento, impuesto, total, estado) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("iidddds", $cliente_id, $mascota_id, $subtotal, $descuento, $impuesto, $total, $estado);
    if ($stmt->execute()) {
        $ultima_factura_id = $conexion->insert_id;
        header("Location: facturacion.php?msg=created");
    } else {
        header("Location: facturacion.php?msg=error");
    }
    exit;
}

// ==================================================
// MOSTRAR MENSAJES DE ÉXITO/ERROR
// ==================================================
$msg = '';
if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {
        case 'created': $msg = '<div class="alert alert-success">✅ Factura creada correctamente.</div>'; break;
        case 'updated': $msg = '<div class="alert alert-success">✏️ Factura actualizada correctamente.</div>'; break;
        case 'deleted': $msg = '<div class="alert alert-success">🗑️ Factura eliminada correctamente.</div>'; break;
        case 'error': $msg = '<div class="alert alert-danger">❌ Ocurrió un error. Intenta nuevamente.</div>'; break;
    }
}

// ==================================================
// CONSULTAS PARA MOSTRAR FORM Y TABLA
// ==================================================
$clientes = $conexion->query("SELECT * FROM clientes ORDER BY nombre ASC");
$mascotas = $conexion->query("
    SELECT mascotas.id, mascotas.nombre, clientes.nombre AS dueño 
    FROM mascotas 
    INNER JOIN clientes ON mascotas.cliente_id = clientes.id
");
$facturas = $conexion->query("
    SELECT facturas.id, clientes.nombre AS cliente, mascotas.nombre AS mascota, facturas.fecha, 
           facturas.subtotal, facturas.descuento, facturas.impuesto, facturas.total, facturas.estado
    FROM facturas
    INNER JOIN clientes ON facturas.cliente_id = clientes.id
    LEFT JOIN mascotas ON facturas.mascota_id = mascotas.id
    ORDER BY facturas.id DESC
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Módulo Facturación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body{ background: linear-gradient(135deg,#0f172a,#1e293b,#334155); min-height:100vh; }
        .card-custom{ border:none; border-radius:25px; background:rgba(255,255,255,0.97); box-shadow:0 10px 30px rgba(0,0,0,0.25); }
        .titulo{ font-weight:bold; color:#0f172a; }
        .subtitulo{ color:#64748b; }
        .form-control, .form-select{ border-radius:12px; padding:12px; }
        .btn-custom{ border-radius:12px; font-weight:600; padding:10px 20px; }
        .table{ border-radius:15px; overflow:hidden; }
        .icon-box{ width:80px; height:80px; border-radius:20px; background:#f59e0b; color:white; display:flex; justify-content:center; align-items:center; font-size:35px; margin:auto; margin-bottom:20px; box-shadow:0 5px 15px rgba(245,158,11,0.4); }
        .action-buttons .btn { margin: 0 3px; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card card-custom p-5">
                <div class="icon-box"><i class="bi bi-receipt-cutoff"></i></div>
                <div class="text-center mb-4">
                    <h1 class="titulo">🧾 Sistema de Facturación</h1>
                    <p class="subtitulo">Gestión completa de facturas (Crear, Editar, Eliminar, Visualizar)</p>
                </div>

                <?= $msg ?>

                <!-- Formulario de creación -->
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">👤 Cliente</label>
                            <select name="cliente_id" class="form-select" required>
                                <option value="">Seleccionar cliente</option>
                                <?php while($c = $clientes->fetch_assoc()): ?>
                                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">🐶 Mascota</label>
                            <select name="mascota_id" class="form-select">
                                <option value="">Sin mascota</option>
                                <?php while($m = $mascotas->fetch_assoc()): ?>
                                    <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nombre']) ?> - Dueño: <?= htmlspecialchars($m['dueño']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="form-label fw-bold">💰 Subtotal</label>
                            <input type="number" step="0.01" name="subtotal" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="form-label fw-bold">🏷 Descuento</label>
                            <input type="number" step="0.01" name="descuento" class="form-control" value="0">
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="form-label fw-bold">📈 Impuesto</label>
                            <input type="number" step="0.01" name="impuesto" class="form-control" value="0">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">📌 Estado</label>
                            <select name="estado" class="form-select">
                                <option value="Pendiente">Pendiente</option>
                                <option value="Pagada">Pagada</option>
                                <option value="Parcial">Parcial</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">🧮 Fórmula</label>
                            <input type="text" class="form-control" value="Total = Subtotal - Descuento + Impuesto" readonly>
                        </div>
                    </div>
                    <div class="d-flex gap-3 justify-content-center flex-wrap mt-3">
                        <button type="submit" class="btn btn-warning btn-custom"><i class="bi bi-plus-circle"></i> Generar Factura</button>
                        <a href="../index.php" class="btn btn-dark btn-custom"><i class="bi bi-arrow-left"></i> Volver al Menú</a>
                    </div>
                </form>

                <hr class="my-5">

                <!-- Tabla de facturas con botones Ver, Editar, Eliminar -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle text-center">
                        <thead class="table-dark">
                            <tr><th>ID</th><th>Cliente</th><th>Mascota</th><th>Fecha</th><th>Subtotal</th><th>Descuento</th><th>Impuesto</th><th>Total</th><th>Estado</th><th>Acciones</th></tr>
                        </thead>
                        <tbody>
                        <?php while($f = $facturas->fetch_assoc()): ?>
                            <tr>
                                <td><?= $f['id'] ?></td>
                                <td>👤 <?= htmlspecialchars($f['cliente']) ?></td>
                                <td><?= $f['mascota'] ? "🐶 ".htmlspecialchars($f['mascota']) : "<span class='text-muted'>Sin mascota</span>" ?></td>
                                <td><?= $f['fecha'] ?></td>
                                <td>Bs <?= number_format($f['subtotal'], 2) ?></td>
                                <td>Bs <?= number_format($f['descuento'], 2) ?></td>
                                <td>Bs <?= number_format($f['impuesto'], 2) ?></td>
                                <td><strong class="text-success">Bs <?= number_format($f['total'], 2) ?></strong></td>
                                <td>
                                    <?php if($f['estado'] == 'Pendiente'): ?>
                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                    <?php elseif($f['estado'] == 'Pagada'): ?>
                                        <span class="badge bg-success">Pagada</span>
                                    <?php else: ?>
                                        <span class="badge bg-primary">Parcial</span>
                                    <?php endif; ?>
                                </td>
                                <td class="action-buttons">
                                    <a href="?action=view&id=<?= $f['id'] ?>" target="_blank" class="btn btn-sm btn-outline-info" title="Ver Factura">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="?action=edit&id=<?= $f['id'] ?>" class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="javascript:void(0);" onclick="confirmDelete(<?= $f['id'] ?>)" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                        <i class="bi bi-trash3"></i>
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

<script>
function confirmDelete(id) {
    if (confirm('¿Estás seguro de que deseas eliminar la factura #' + id + '? Esta acción no se puede deshacer.')) {
        window.location.href = '?action=delete&id=' + id;
    }
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php include '../includes/footer.php'; ?>
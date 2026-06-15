<?php
include '../config/database.php';
include '../includes/header.php';

// ==================================================
// VER FACTURA (igual que en facturacion.php)
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
                <a href="reporte_facturas.php" class="btn-volver"><i class="bi bi-arrow-left"></i> Volver al reporte</a>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
    exit;
}

// ==================================================
// LISTADO DE FACTURAS (SÓLO LECTURA)
// ==================================================
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
    <title>Reporte de Facturas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body{ background: linear-gradient(135deg,#0f172a,#1e293b,#334155); min-height:100vh; }
        .card-custom{ border:none; border-radius:25px; background:rgba(255,255,255,0.97); box-shadow:0 10px 30px rgba(0,0,0,0.25); }
        .table{ border-radius:15px; overflow:hidden; }
        .btn-ver { background: #0d6efd; color: white; border-radius: 20px; padding: 5px 15px; text-decoration: none; }
        .btn-ver:hover { background: #0b5ed7; color: white; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card card-custom p-5">
                <div class="text-center mb-4">
                    <h1 class="titulo" style="font-weight:bold; color:#0f172a;">📋 Reporte General de Facturas</h1>
                    <p class="subtitulo" style="color:#64748b;">Solo consulta (sin edición ni eliminación)</p>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle text-center">
                        <thead class="table-dark">
                            <tr><th>ID</th><th>Cliente</th><th>Mascota</th><th>Fecha</th><th>Subtotal</th><th>Descuento</th><th>Impuesto</th><th>Total</th><th>Estado</th><th>Detalle</th></tr>
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
                                <td>
                                    <a href="?action=view&id=<?= $f['id'] ?>" target="_blank" class="btn-ver">
                                        <i class="bi bi-eye"></i> Ver
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <div class="text-center mt-4">
                    <a href="../index.php" class="btn btn-secondary"><i class="bi bi-house-door"></i> Volver al Inicio</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php include '../includes/footer.php'; ?>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// ============================================================
//  pagos.php  –  Clínica Veterinaria La Unión
//  Diseño estilo "módulo mascotas" con editar/eliminar
// ============================================================

// ---------- CONFIGURACIÓN ----------
$host   = 'localhost';
$db     = 'clinica_veterinaria';
$user   = 'root';
$pass   = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die('<div class="alert alert-danger m-4">Error de conexión: ' . htmlspecialchars($e->getMessage()) . '</div>');
}

// ---------- DATOS DE QR (solo visual) ----------
$qr_numero_cuenta = "4-0765432-1-8";
$qr_nombre_cuenta = "Clínica Veterinaria La Unión";
$qr_banco         = "Banco Nacional de Bolivia";

// ---------- PROCESAR FORMULARIOS ----------
$mensaje = '';
$tipo_msg = '';

// Registrar pago
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'registrar') {
    $factura_id = (int)$_POST['factura_id'];
    $monto = (float)str_replace(',', '.', $_POST['monto']);
    $metodo_pago = $_POST['metodo_pago'];
    $metodos_validos = ['Efectivo', 'Tarjeta', 'Transferencia', 'QR'];
    if (!in_array($metodo_pago, $metodos_validos)) $metodo_pago = 'Efectivo';

    if ($factura_id > 0 && $monto > 0) {
        $stmt = $pdo->prepare("INSERT INTO pagos (factura_id, monto, metodo_pago) VALUES (?, ?, ?)");
        $stmt->execute([$factura_id, $monto, $metodo_pago]);

        $stmt2 = $pdo->prepare("
            SELECT f.total, COALESCE(SUM(p.monto),0) as pagado
            FROM facturas f
            LEFT JOIN pagos p ON p.factura_id = f.id
            WHERE f.id = ?
            GROUP BY f.id
        ");
        $stmt2->execute([$factura_id]);
        $row = $stmt2->fetch();
        if ($row) {
            $nuevo_estado = $row['pagado'] >= $row['total'] ? 'Pagada' : ($row['pagado'] > 0 ? 'Parcial' : 'Pendiente');
            $pdo->prepare("UPDATE facturas SET estado = ? WHERE id = ?")->execute([$nuevo_estado, $factura_id]);
        }
        $mensaje = "✅ Pago registrado correctamente.";
        $tipo_msg = "success";
    } else {
        $mensaje = "⚠️ Datos inválidos.";
        $tipo_msg = "warning";
    }
}

// Editar pago
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'editar') {
    $pago_id = (int)$_POST['pago_id'];
    $nuevo_monto = (float)str_replace(',', '.', $_POST['monto']);
    $nuevo_metodo = $_POST['metodo_pago'];
    $metodos_validos = ['Efectivo', 'Tarjeta', 'Transferencia', 'QR'];
    if (!in_array($nuevo_metodo, $metodos_validos)) $nuevo_metodo = 'Efectivo';

    $stmt = $pdo->prepare("SELECT factura_id FROM pagos WHERE id = ?");
    $stmt->execute([$pago_id]);
    $factura_id = $stmt->fetchColumn();

    if ($factura_id && $nuevo_monto > 0) {
        $upd = $pdo->prepare("UPDATE pagos SET monto = ?, metodo_pago = ? WHERE id = ?");
        $upd->execute([$nuevo_monto, $nuevo_metodo, $pago_id]);

        $stmt2 = $pdo->prepare("
            SELECT f.total, COALESCE(SUM(p.monto),0) as pagado
            FROM facturas f
            LEFT JOIN pagos p ON p.factura_id = f.id
            WHERE f.id = ?
            GROUP BY f.id
        ");
        $stmt2->execute([$factura_id]);
        $row = $stmt2->fetch();
        if ($row) {
            $nuevo_estado = $row['pagado'] >= $row['total'] ? 'Pagada' : ($row['pagado'] > 0 ? 'Parcial' : 'Pendiente');
            $pdo->prepare("UPDATE facturas SET estado = ? WHERE id = ?")->execute([$nuevo_estado, $factura_id]);
        }
        $mensaje = "✏️ Pago actualizado.";
        $tipo_msg = "info";
    } else {
        $mensaje = "⚠️ Error al editar.";
        $tipo_msg = "warning";
    }
}

// Eliminar pago
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
    $pago_id = (int)$_POST['pago_id'];
    $stmt = $pdo->prepare("SELECT factura_id FROM pagos WHERE id = ?");
    $stmt->execute([$pago_id]);
    $factura_id = $stmt->fetchColumn();

    if ($factura_id) {
        $pdo->prepare("DELETE FROM pagos WHERE id = ?")->execute([$pago_id]);
        $stmt2 = $pdo->prepare("
            SELECT f.total, COALESCE(SUM(p.monto),0) as pagado
            FROM facturas f
            LEFT JOIN pagos p ON p.factura_id = f.id
            WHERE f.id = ?
            GROUP BY f.id
        ");
        $stmt2->execute([$factura_id]);
        $row = $stmt2->fetch();
        if ($row) {
            $nuevo_estado = $row['pagado'] >= $row['total'] ? 'Pagada' : ($row['pagado'] > 0 ? 'Parcial' : 'Pendiente');
            $pdo->prepare("UPDATE facturas SET estado = ? WHERE id = ?")->execute([$nuevo_estado, $factura_id]);
        }
        $mensaje = "🗑️ Pago eliminado.";
        $tipo_msg = "info";
    } else {
        $mensaje = "⚠️ No se encontró el pago.";
        $tipo_msg = "warning";
    }
}

// ---------- OBTENER DATOS PARA LA VISTA ----------
$facturas_disponibles = $pdo->query("
    SELECT f.id, f.total, COALESCE(SUM(p.monto),0) as pagado, c.nombre as cliente
    FROM facturas f
    JOIN clientes c ON c.id = f.cliente_id
    LEFT JOIN pagos p ON p.factura_id = f.id
    GROUP BY f.id
    HAVING pagado < f.total
    ORDER BY f.id DESC
")->fetchAll();

$pagos = $pdo->query("
    SELECT p.id, p.fecha_pago, p.monto, p.metodo_pago,
           f.id as factura_id, f.total as total_factura, f.estado as estado_factura,
           c.nombre as cliente, m.nombre as mascota
    FROM pagos p
    JOIN facturas f ON f.id = p.factura_id
    JOIN clientes c ON c.id = f.cliente_id
    LEFT JOIN mascotas m ON m.id = f.mascota_id
    ORDER BY p.fecha_pago DESC
")->fetchAll();

$resumen = $pdo->query("
    SELECT
        COUNT(DISTINCT f.id) AS total_facturas,
        COALESCE(SUM(f.total),0) AS monto_total,
        COALESCE(SUM(p.monto),0) AS monto_cobrado,
        COALESCE(SUM(f.total),0) - COALESCE(SUM(p.monto),0) AS monto_pendiente
    FROM facturas f
    LEFT JOIN pagos p ON p.factura_id = f.id
")->fetch();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagos | Clínica Veterinaria La Unión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .container-custom {
            max-width: 1400px;
            margin: 0 auto;
        }
        /* Tarjeta blanca principal */
        .card-white {
            background: #ffffff;
            border: none;
            border-radius: 24px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 30px;
        }
        .card-header-custom {
            background: #ffffff;
            padding: 1.2rem 1.8rem;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        .card-header-custom h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .card-header-custom h2 i {
            color: #2563eb;
        }
        .btn-vet {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: white;
            border: none;
            border-radius: 40px;
            padding: 8px 24px;
            font-weight: 600;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .btn-vet:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(37,99,235,0.3);
            color: white;
        }
        .btn-outline-vet {
            background: transparent;
            border: 1px solid #cbd5e1;
            border-radius: 40px;
            padding: 6px 20px;
            font-weight: 500;
            color: #334155;
            transition: 0.2s;
            text-decoration: none;
        }
        .btn-outline-vet:hover {
            background: #f1f5f9;
            border-color: #2563eb;
            color: #2563eb;
        }
        /* Tarjetas de resumen */
        .resumen-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .resumen-card {
            background: white;
            border-radius: 20px;
            padding: 1.2rem;
            position: relative;
            overflow: hidden;
            border-left: 4px solid;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .resumen-card .label {
            font-size: 0.7rem;
            text-transform: uppercase;
            font-weight: 700;
            color: #64748b;
        }
        .resumen-card .value {
            font-size: 1.7rem;
            font-weight: 800;
            color: #0f172a;
        }
        .resumen-card i {
            position: absolute;
            right: 15px;
            bottom: 15px;
            font-size: 2rem;
            opacity: 0.2;
        }
        .resumen-card.blue { border-left-color: #3b82f6; }
        .resumen-card.green { border-left-color: #10b981; }
        .resumen-card.yellow { border-left-color: #f59e0b; }
        .resumen-card.red { border-left-color: #ef4444; }

        /* Formulario */
        .form-label-custom {
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 6px;
            font-size: 0.85rem;
        }
        .form-control-custom, .form-select-custom {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 14px;
            padding: 10px 14px;
            width: 100%;
            transition: 0.2s;
        }
        .form-control-custom:focus, .form-select-custom:focus {
            border-color: #2563eb;
            outline: none;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.2);
        }
        .metodo-grid {
            display: grid;
            grid-template-columns: repeat(4,1fr);
            gap: 12px;
        }
        .metodo-btn input[type="radio"] { display: none; }
        .metodo-btn label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 10px 6px;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 600;
            color: #334155;
            transition: 0.2s;
        }
        .metodo-btn label i { font-size: 1.2rem; }
        .metodo-btn input:checked + label {
            border-color: #2563eb;
            background: #eff6ff;
            color: #1e40af;
        }
        /* Tabla estilo mascotas */
        .table-vet {
            width: 100%;
            border-collapse: collapse;
        }
        .table-vet thead th {
            background: #0f172a;
            color: #e2e8f0;
            padding: 12px 16px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #1e293b;
        }
        .table-vet tbody td {
            padding: 12px 16px;
            border-bottom: 1px solid #e2e8f0;
            color: #1e293b;
            vertical-align: middle;
        }
        .table-vet tbody tr:hover {
            background: #f8fafc;
        }
        .badge-estado {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 700;
        }
        .badge-pagada { background: #d1fae5; color: #065f46; }
        .badge-parcial { background: #fed7aa; color: #92400e; }
        .badge-pendiente { background: #fee2e2; color: #991b1b; }
        .badge-metodo {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f1f5f9;
            padding: 4px 10px;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        .btn-icon {
            background: none;
            border: none;
            font-size: 1.1rem;
            padding: 6px 10px;
            border-radius: 30px;
            transition: 0.2s;
            cursor: pointer;
        }
        .btn-edit { color: #2563eb; background: #eff6ff; }
        .btn-edit:hover { background: #2563eb; color: white; }
        .btn-delete { color: #dc2626; background: #fee2e2; }
        .btn-delete:hover { background: #dc2626; color: white; }
        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(3px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        .modal-overlay.active { display: flex; }
        .modal-box {
            background: white;
            border-radius: 28px;
            max-width: 500px;
            width: 90%;
            padding: 1.8rem;
            box-shadow: 0 20px 35px rgba(0,0,0,0.3);
        }
        .alert-custom {
            border-radius: 16px;
            padding: 12px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }
        .alert-success { background: #d1fae5; color: #065f46; border-left: 4px solid #10b981; }
        .alert-warning  { background: #fed7aa; color: #92400e; border-left: 4px solid #f59e0b; }
        .alert-info     { background: #dbeafe; color: #1e40af; border-left: 4px solid #3b82f6; }
        @media (max-width: 768px) {
            .resumen-grid { grid-template-columns: repeat(2,1fr); }
            .metodo-grid { grid-template-columns: repeat(2,1fr); }
        }
        /* Encabezado estilo imagen */
        .header-clinica {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }
        .header-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .header-title i {
            font-size: 2rem;
            color: #fbbf24;
        }
        .header-title h1 {
            font-size: 1.8rem;
            font-weight: 800;
            color: white;
            margin: 0;
            letter-spacing: -0.5px;
        }
        .btn-back-header {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 40px;
            padding: 8px 22px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: 0.2s;
        }
        .btn-back-header:hover {
            background: rgba(255,255,255,0.2);
            color: white;
        }
    </style>
</head>
<body>

<div class="container-custom">
    <!-- ENCABEZADO IGUAL A LA IMAGEN -->
    <div class="header-clinica">
        <div class="header-title">
            <i class="bi bi-paw"></i>
            <h1>Clínica Veterinaria La Unión</h1>
        </div>
        <a href="../index.php" class="btn-back-header">
            <i class="bi bi-arrow-left"></i> Volver al Menú
        </a>
    </div>

    <!-- Mensajes -->
    <?php if ($mensaje): ?>
    <div class="alert-custom alert-<?= $tipo_msg ?>">
        <i class="bi bi-info-circle-fill"></i> <?= htmlspecialchars($mensaje) ?>
    </div>
    <?php endif; ?>

    <!-- Tarjetas de resumen -->
    <div class="resumen-grid">
        <div class="resumen-card blue">
            <div class="label">Total Facturas</div>
            <div class="value"><?= $resumen['total_facturas'] ?></div>
            <i class="bi bi-receipt"></i>
        </div>
        <div class="resumen-card green">
            <div class="label">Monto Cobrado</div>
            <div class="value">Bs <?= number_format($resumen['monto_cobrado'], 2) ?></div>
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div class="resumen-card yellow">
            <div class="label">Monto Pendiente</div>
            <div class="value">Bs <?= number_format($resumen['monto_pendiente'], 2) ?></div>
            <i class="bi bi-clock-fill"></i>
        </div>
        <div class="resumen-card red">
            <div class="label">Total General</div>
            <div class="value">Bs <?= number_format($resumen['monto_total'], 2) ?></div>
            <i class="bi bi-currency-exchange"></i>
        </div>
    </div>

    <!-- Tarjeta principal (formulario + tabla) -->
    <div class="card-white">
        <div class="card-header-custom">
            <h2><i class="bi bi-credit-card-2-front-fill"></i> Registro de Pagos</h2>
            <button class="btn-vet" onclick="abrirModalRegistro()">
                <i class="bi bi-plus-circle-fill"></i> Nuevo Pago
            </button>
        </div>

        <!-- Tabla de pagos existentes -->
        <div style="padding: 0 1.5rem 1.5rem 1.5rem;">
            <div class="table-responsive">
                <table class="table-vet">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Mascota</th>
                            <th>Factura #</th>
                            <th>Monto</th>
                            <th>Método</th>
                            <th>Estado Factura</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pagos)): ?>
                            <tr><td colspan="9" class="text-center py-4 text-secondary">No hay pagos registrados aún.</td></tr>
                        <?php else: ?>
                            <?php foreach ($pagos as $p):
                                $icono_metodo = match($p['metodo_pago']) {
                                    'Efectivo' => 'bi-cash',
                                    'Tarjeta' => 'bi-credit-card-fill',
                                    'Transferencia' => 'bi-bank',
                                    'QR' => 'bi-qr-code-scan',
                                    default => 'bi-cash'
                                };
                                $clase_estado = match($p['estado_factura']) {
                                    'Pagada' => 'badge-pagada',
                                    'Parcial' => 'badge-parcial',
                                    default => 'badge-pendiente'
                                };
                            ?>
                            <tr>
                                <td><strong>#<?= $p['id'] ?></strong></td>
                                <td><?= date('d/m/Y H:i', strtotime($p['fecha_pago'])) ?></td>
                                <td><?= htmlspecialchars($p['cliente']) ?></td>
                                <td><?= htmlspecialchars($p['mascota'] ?? '—') ?></td>
                                <td><strong>#<?= $p['factura_id'] ?></strong></td>
                                <td class="fw-bold text-success">Bs <?= number_format($p['monto'], 2) ?></td>
                                <td><span class="badge-metodo"><i class="bi <?= $icono_metodo ?>"></i> <?= $p['metodo_pago'] ?></span></td>
                                <td><span class="badge-estado <?= $clase_estado ?>"><?= $p['estado_factura'] ?></span></td>
                                <td>
                                    <button class="btn-icon btn-edit" onclick='editarPago(<?= json_encode($p) ?>)' title="Editar pago">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <form method="POST" style="display: inline-block;" onsubmit="return confirm('¿Eliminar este pago?')">
                                        <input type="hidden" name="accion" value="eliminar">
                                        <input type="hidden" name="pago_id" value="<?= $p['id'] ?>">
                                        <button type="submit" class="btn-icon btn-delete" title="Eliminar pago">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Panel adicional con QR (estilo informativo) -->
    <div class="card-white">
        <div class="card-header-custom">
            <h2><i class="bi bi-qr-code-scan"></i> Pago por QR - Transferencia</h2>
        </div>
        <div style="padding: 0 1.5rem 1.5rem 1.5rem;">
            <div class="row align-items-center">
                <div class="col-md-4 text-center">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=<?= urlencode("PAGO CLINICA VETERINARIA LA UNION\nBanco: $qr_banco\nCuenta: $qr_numero_cuenta\nTitular: $qr_nombre_cuenta") ?>" alt="QR" class="img-fluid" style="border-radius: 16px;">
                </div>
                <div class="col-md-8">
                    <p><strong><?= htmlspecialchars($qr_nombre_cuenta) ?></strong><br>
                    🏦 <?= htmlspecialchars($qr_banco) ?><br>
                    💳 Cuenta: <strong><?= htmlspecialchars($qr_numero_cuenta) ?></strong><br>
                    📍 La Paz – Cementerio General</p>
                    <small class="text-secondary">Escanea el código QR para pagar. Luego registra el pago con el método "QR".</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL REGISTRAR PAGO -->
<div class="modal-overlay" id="modalRegistro">
    <div class="modal-box">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold">💳 Registrar Pago</h5>
            <button class="btn-close" onclick="cerrarModal('modalRegistro')"></button>
        </div>
        <form method="POST">
            <input type="hidden" name="accion" value="registrar">
            <div class="mb-3">
                <label class="form-label-custom">Factura</label>
                <select name="factura_id" id="factura_select" class="form-select-custom" required>
                    <option value="">-- Seleccione factura --</option>
                    <?php foreach ($facturas_disponibles as $f):
                        $saldo = $f['total'] - $f['pagado'];
                    ?>
                        <option value="<?= $f['id'] ?>" data-saldo="<?= $saldo ?>">
                            #<?= $f['id'] ?> - <?= htmlspecialchars($f['cliente']) ?> (Saldo: Bs <?= number_format($saldo,2) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label-custom">Monto (Bs)</label>
                <input type="number" name="monto" id="monto_reg" class="form-control-custom" step="0.01" min="0.01" required>
                <div id="saldo_hint" class="small text-muted mt-1"></div>
            </div>
            <div class="mb-4">
                <label class="form-label-custom">Método de Pago</label>
                <div class="metodo-grid">
                    <div class="metodo-btn"><input type="radio" name="metodo_pago" value="Efectivo" id="reg_efectivo" checked><label for="reg_efectivo"><i class="bi bi-cash"></i>Efectivo</label></div>
                    <div class="metodo-btn"><input type="radio" name="metodo_pago" value="Tarjeta" id="reg_tarjeta"><label for="reg_tarjeta"><i class="bi bi-credit-card-fill"></i>Tarjeta</label></div>
                    <div class="metodo-btn"><input type="radio" name="metodo_pago" value="Transferencia" id="reg_transfer"><label for="reg_transfer"><i class="bi bi-bank"></i>Transferencia</label></div>
                    <div class="metodo-btn"><input type="radio" name="metodo_pago" value="QR" id="reg_qr"><label for="reg_qr"><i class="bi bi-qr-code-scan"></i>QR</label></div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn-outline-vet flex-fill" onclick="cerrarModal('modalRegistro')">Cancelar</button>
                <button type="submit" class="btn-vet flex-fill">Confirmar Pago</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDITAR PAGO -->
<div class="modal-overlay" id="modalEdicion">
    <div class="modal-box">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold">✏️ Editar Pago</h5>
            <button class="btn-close" onclick="cerrarModal('modalEdicion')"></button>
        </div>
        <form method="POST">
            <input type="hidden" name="accion" value="editar">
            <input type="hidden" name="pago_id" id="edit_pago_id">
            <div class="mb-3">
                <label class="form-label-custom">Nuevo Monto (Bs)</label>
                <input type="number" name="monto" id="edit_monto" class="form-control-custom" step="0.01" min="0.01" required>
            </div>
            <div class="mb-4">
                <label class="form-label-custom">Método de Pago</label>
                <div class="metodo-grid">
                    <div class="metodo-btn"><input type="radio" name="metodo_pago" value="Efectivo" id="edit_efectivo"><label for="edit_efectivo"><i class="bi bi-cash"></i>Efectivo</label></div>
                    <div class="metodo-btn"><input type="radio" name="metodo_pago" value="Tarjeta" id="edit_tarjeta"><label for="edit_tarjeta"><i class="bi bi-credit-card-fill"></i>Tarjeta</label></div>
                    <div class="metodo-btn"><input type="radio" name="metodo_pago" value="Transferencia" id="edit_transfer"><label for="edit_transfer"><i class="bi bi-bank"></i>Transferencia</label></div>
                    <div class="metodo-btn"><input type="radio" name="metodo_pago" value="QR" id="edit_qr"><label for="edit_qr"><i class="bi bi-qr-code-scan"></i>QR</label></div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn-outline-vet flex-fill" onclick="cerrarModal('modalEdicion')">Cancelar</button>
                <button type="submit" class="btn-vet flex-fill">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<script>
    function abrirModalRegistro() {
        document.getElementById('modalRegistro').classList.add('active');
        const sel = document.getElementById('factura_select');
        actualizarSaldo(sel);
    }
    function cerrarModal(id) {
        document.getElementById(id).classList.remove('active');
    }
    function actualizarSaldo(sel) {
        const opt = sel.options[sel.selectedIndex];
        const saldo = parseFloat(opt.getAttribute('data-saldo') || 0);
        const hint = document.getElementById('saldo_hint');
        const montoInput = document.getElementById('monto_reg');
        if (saldo > 0) {
            hint.innerHTML = `💡 Saldo pendiente: Bs ${saldo.toFixed(2)}`;
            montoInput.value = saldo.toFixed(2);
            montoInput.max = saldo;
        } else {
            hint.innerHTML = '';
            montoInput.value = '';
        }
    }
    document.getElementById('factura_select')?.addEventListener('change', function() { actualizarSaldo(this); });

    function editarPago(pago) {
        document.getElementById('edit_pago_id').value = pago.id;
        document.getElementById('edit_monto').value = pago.monto;
        const metodo = pago.metodo_pago;
        document.querySelectorAll('#modalEdicion input[name="metodo_pago"]').forEach(radio => {
            radio.checked = (radio.value === metodo);
        });
        document.getElementById('modalEdicion').classList.add('active');
    }

    window.onclick = function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            e.target.classList.remove('active');
        }
    }
</script>
</body>
</html>
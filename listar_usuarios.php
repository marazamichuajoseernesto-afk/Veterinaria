<?php
session_start();
include 'config/database.php';

// Verificar que el usuario haya iniciado sesión y sea Administrador
if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'Administrador') {
    header("Location: login.php");
    exit();
}

// Consultar todos los usuarios
$sql = "SELECT id, usuario, nombre, rol, created_at FROM usuarios ORDER BY rol, usuario";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Usuarios - Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: #f0f2f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container-custom {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
        }
        .card-header-custom {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: white;
            border-radius: 20px 20px 0 0;
            padding: 15px 20px;
        }
        .table thead {
            background: #eef2ff;
        }
        .badge-admin {
            background-color: #dc2626;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
        }
        .badge-cliente {
            background-color: #16a34a;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
        }
        .btn-volver {
            background: #0f172a;
            color: white;
            border-radius: 30px;
            padding: 8px 20px;
            text-decoration: none;
        }
        .btn-volver:hover {
            background: #1e293b;
            color: white;
        }
    </style>
</head>
<body>

<div class="container-custom">
    <div class="card shadow">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <h3 class="mb-0"><i class="bi bi-people-fill"></i> Listado de Usuarios</h3>
            <a href="index.php" class="btn-volver"><i class="bi bi-arrow-left"></i> Volver al Panel</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Nombre completo</th>
                            <th>Rol</th>
                            <th>Fecha de registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($resultado && $resultado->num_rows > 0): ?>
                            <?php while ($fila = $resultado->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($fila['id']) ?></td>
                                    <td><?= htmlspecialchars($fila['usuario']) ?></td>
                                    <td><?= htmlspecialchars($fila['nombre']) ?></td>
                                    <td>
                                        <?php if ($fila['rol'] == 'Administrador'): ?>
                                            <span class="badge-admin"><i class="bi bi-shield-lock-fill"></i> Administrador</span>
                                        <?php else: ?>
                                            <span class="badge-cliente"><i class="bi bi-person-check-fill"></i> Cliente</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($fila['created_at'])) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No hay usuarios registrados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
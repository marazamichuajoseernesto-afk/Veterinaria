<?php
include '../config/database.php';
include '../includes/header.php';

// =========================
// MOSTRAR CITAS
// =========================
$resultado = $conexion->query("
SELECT 
    citas.id,
    mascotas.nombre AS mascota,
    clientes.nombre AS dueño,
    veterinarios.nombre AS veterinario,
    citas.fecha,
    citas.hora,
    citas.motivo,
    citas.estado
FROM citas

INNER JOIN mascotas
ON citas.mascota_id = mascotas.id

INNER JOIN clientes
ON mascotas.cliente_id = clientes.id

LEFT JOIN veterinarios
ON citas.veterinario_id = veterinarios.id

ORDER BY citas.id DESC
");
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Listado de Citas</title>

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

        .badge{
            padding:8px 12px;
            border-radius:10px;
            font-size:14px;
        }

    </style>

</head>

<body>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-12">

            <div class="card card-custom p-5">

                <!-- TITULO -->
                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>

                        <h1 class="titulo">
                            📅 Registro de Citas
                        </h1>

                        <p class="text-muted">
                            Historial y control de citas veterinarias
                        </p>

                    </div>

                    <div class="d-flex gap-2">

                        <!-- NUEVA CITA -->
                        <a href="citas.php"
                           class="btn btn-primary btn-custom">

                            <i class="bi bi-plus-circle"></i>
                            Nueva Cita

                        </a>

                        <!-- VOLVER -->
                        <a href="../index.php"
                           class="btn btn-dark btn-custom">

                            <i class="bi bi-arrow-left"></i>
                            Menú

                        </a>

                    </div>

                </div>

                <!-- TABLA -->
                <div class="table-responsive">

                    <table class="table table-hover table-bordered align-middle text-center">

                        <thead class="table-dark">

                            <tr>

                                <th>ID</th>
                                <th>Mascota</th>
                                <th>Dueño</th>
                                <th>Veterinario</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Motivo</th>
                                <th>Estado</th>

                            </tr>

                        </thead>

                        <tbody>

                        <?php while($fila = $resultado->fetch_assoc()): ?>

                            <tr>

                                <td>
                                    <?= $fila['id'] ?>
                                </td>

                                <td>
                                    🐶 <?= $fila['mascota'] ?>
                                </td>

                                <td>
                                    👤 <?= $fila['dueño'] ?>
                                </td>

                                <td>

                                    <?php if($fila['veterinario']){ ?>

                                        👨‍⚕️ <?= $fila['veterinario'] ?>

                                    <?php } else { ?>

                                        <span class="text-muted">
                                            Sin asignar
                                        </span>

                                    <?php } ?>

                                </td>

                                <td>
                                    <?= $fila['fecha'] ?>
                                </td>

                                <td>
                                    <?= $fila['hora'] ?>
                                </td>

                                <td>
                                    <?= $fila['motivo'] ?>
                                </td>

                                <td>

                                    <?php
                                    if($fila['estado'] == 'Pendiente'){
                                        echo "<span class='badge bg-warning text-dark'>Pendiente</span>";
                                    }

                                    elseif($fila['estado'] == 'Confirmada'){
                                        echo "<span class='badge bg-primary'>Confirmada</span>";
                                    }

                                    elseif($fila['estado'] == 'Atendida'){
                                        echo "<span class='badge bg-success'>Atendida</span>";
                                    }

                                    else{
                                        echo "<span class='badge bg-danger'>Cancelada</span>";
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

</body>
</html>

<?php include '../includes/footer.php'; ?>
<?php
include '../config/database.php';
include '../includes/header.php';

// =========================
// GUARDAR HISTORIAL
// =========================
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $mascota_id   = $_POST['mascota_id'];
    $cita_id      = !empty($_POST['cita_id']) ? $_POST['cita_id'] : "NULL";
    $motivo       = $_POST['motivo'];
    $diagnostico  = $_POST['diagnostico'];
    $tratamiento  = $_POST['tratamiento'];
    $alergias     = $_POST['alergias'];
    $vacunas      = $_POST['vacunas'];
    $peso         = $_POST['peso'];

    $sql = "INSERT INTO historiales_medicos
            (mascota_id, cita_id, motivo, diagnostico, tratamiento, alergias, vacunas, peso)
            VALUES
            ('$mascota_id', $cita_id, '$motivo', '$diagnostico',
             '$tratamiento', '$alergias', '$vacunas', '$peso')";

    if ($conexion->query($sql)) {

        echo "
        <div class='container mt-3'>
            <div class='alert alert-success alert-dismissible fade show'>
                ✅ Historial clínico registrado correctamente
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
// MASCOTAS
// =========================
$mascotas = $conexion->query("
SELECT mascotas.id,
       mascotas.nombre,
       clientes.nombre AS dueño
FROM mascotas
INNER JOIN clientes
ON mascotas.cliente_id = clientes.id
");

// =========================
// CITAS
// =========================
$citas = $conexion->query("
SELECT citas.id,
       mascotas.nombre AS mascota
FROM citas
INNER JOIN mascotas
ON citas.mascota_id = mascotas.id
");
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <title>Historial Clínico</title>

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

        .form-control,
        .form-select{
            border-radius:12px;
            padding:12px;
        }

        .btn-custom{
            border-radius:12px;
            padding:10px 25px;
            font-weight:600;
        }

        .icon-box{
            width:80px;
            height:80px;
            border-radius:20px;
            background:#0ea5e9;
            display:flex;
            justify-content:center;
            align-items:center;
            color:white;
            font-size:35px;
            margin:auto;
            margin-bottom:20px;
            box-shadow:0 5px 15px rgba(14,165,233,0.4);
        }

    </style>

</head>

<body>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-10">

            <div class="card card-custom p-5">

                <!-- ICONO -->
                <div class="icon-box">
                    <i class="bi bi-clipboard2-pulse-fill"></i>
                </div>

                <!-- TITULO -->
                <div class="text-center mb-4">

                    <h1 class="titulo">
                        🏥 Historial Clínico Veterinario
                    </h1>

                    <p class="subtitulo">
                        Registro de diagnósticos, tratamientos y observaciones médicas
                    </p>

                </div>

                <!-- FORMULARIO -->
                <form method="POST">

                    <div class="row">

                        <!-- MASCOTA -->
                        <div class="col-md-6 mb-4">

                            <label class="form-label fw-bold">
                                🐶 Mascota
                            </label>

                            <select name="mascota_id"
                                    class="form-select"
                                    required>

                                <option value="">
                                    Seleccionar mascota
                                </option>

                                <?php while($m = $mascotas->fetch_assoc()) { ?>

                                    <option value="<?= $m['id'] ?>">

                                        <?= $m['nombre'] ?>
                                        - Dueño:
                                        <?= $m['dueño'] ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                        <!-- CITA -->
                        <div class="col-md-6 mb-4">

                            <label class="form-label fw-bold">
                                📅 Cita Relacionada
                            </label>

                            <select name="cita_id"
                                    class="form-select">

                                <option value="">
                                    Sin cita
                                </option>

                                <?php while($c = $citas->fetch_assoc()) { ?>

                                    <option value="<?= $c['id'] ?>">

                                        Cita #<?= $c['id'] ?>
                                        - <?= $c['mascota'] ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                        <!-- MOTIVO -->
                        <div class="col-md-6 mb-4">

                            <label class="form-label fw-bold">
                                🩺 Motivo
                            </label>

                            <textarea name="motivo"
                                      class="form-control"
                                      rows="3"
                                      required></textarea>

                        </div>

                        <!-- DIAGNOSTICO -->
                        <div class="col-md-6 mb-4">

                            <label class="form-label fw-bold">
                                🔍 Diagnóstico
                            </label>

                            <textarea name="diagnostico"
                                      class="form-control"
                                      rows="3"
                                      required></textarea>

                        </div>

                        <!-- TRATAMIENTO -->
                        <div class="col-md-6 mb-4">

                            <label class="form-label fw-bold">
                                💊 Tratamiento
                            </label>

                            <textarea name="tratamiento"
                                      class="form-control"
                                      rows="3"></textarea>

                        </div>

                        <!-- ALERGIAS -->
                        <div class="col-md-6 mb-4">

                            <label class="form-label fw-bold">
                                ⚠️ Alergias
                            </label>

                            <textarea name="alergias"
                                      class="form-control"
                                      rows="3"></textarea>

                        </div>

                        <!-- VACUNAS -->
                        <div class="col-md-6 mb-4">

                            <label class="form-label fw-bold">
                                💉 Vacunas
                            </label>

                            <textarea name="vacunas"
                                      class="form-control"
                                      rows="3"></textarea>

                        </div>

                        <!-- PESO -->
                        <div class="col-md-6 mb-4">

                            <label class="form-label fw-bold">
                                ⚖️ Peso (kg)
                            </label>

                            <input type="number"
                                   step="0.01"
                                   name="peso"
                                   class="form-control"
                                   placeholder="Ej: 12.50">

                        </div>

                    </div>

                    <!-- BOTONES -->
                 <div class="d-flex gap-3 justify-content-center flex-wrap mt-3">

    <!-- GUARDAR -->
    <button type="submit"
            class="btn btn-primary btn-custom">

        <i class="bi bi-save"></i>
        Guardar Historial

    </button>

    <!-- VER REGISTRO -->
    <a href="registro_historial.php"
       class="btn btn-success btn-custom">

        <i class="bi bi-card-list"></i>
        Ver Registro

    </a>

    <!-- VOLVER -->
    <a href="../index.php"
       class="btn btn-dark btn-custom">

        <i class="bi bi-arrow-left"></i>
        Volver al Menú

    </a>


                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php include '../includes/footer.php'; ?>
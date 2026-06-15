<?php
include 'config/database.php';

// Registrar pago
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $factura_id = $_POST['factura_id'];
    $monto = $_POST['monto'];
    $metodo_pago = $_POST['metodo_pago'];
    $referencia = $_POST['referencia'];

    $sql = "INSERT INTO pagos (factura_id, monto, metodo_pago, referencia)
            VALUES ('$factura_id', '$monto', '$metodo_pago', '$referencia')";

    if ($conexion->query($sql)) {
        echo "<script>alert('Pago registrado correctamente'); window.location='index.php';</script>";
    } else {
        echo "Error: " . $conexion->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Pagos</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        body{
            background: linear-gradient(135deg,#0f172a,#1e3a8a);
            min-height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            font-family:Arial, Helvetica, sans-serif;
        }

        .card{
            width:500px;
            border:none;
            border-radius:20px;
            box-shadow:0 10px 30px rgba(0,0,0,0.4);
            overflow:hidden;
        }

        .card-header{
            background:#0d6efd;
            color:white;
            text-align:center;
            padding:20px;
            font-size:25px;
            font-weight:bold;
        }

        .btn-primary{
            width:100%;
            border-radius:10px;
            padding:10px;
            font-size:18px;
        }

        .form-control, .form-select{
            border-radius:10px;
        }
    </style>
</head>
<body>

<div class="card">
    
    <div class="card-header">
        Registrar Pago
    </div>

    <div class="card-body">

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Factura</label>
                <select name="factura_id" class="form-select" required>

                    <option value="">Seleccione una factura</option>

                    <?php
                    $facturas = $conexion->query("SELECT * FROM facturas");

                    while($f = $facturas->fetch_assoc()){
                        echo "<option value='".$f['id']."'>
                                Factura #".$f['id']."
                              </option>";
                    }
                    ?>

                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Monto</label>
                <input type="number" step="0.01" name="monto" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Método de Pago</label>

                <select name="metodo_pago" class="form-select" required>
                    <option value="Efectivo">Efectivo</option>
                    <option value="Tarjeta">Tarjeta</option>
                    <option value="Transferencia">Transferencia</option>
                    <option value="QR">QR</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Referencia</label>
                <input type="text" name="referencia" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">
                Guardar Pago
            </button>

        </form>

    </div>
</div>

</body>
</html>
<?php
session_start();
include 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    $rol_seleccionado = $_POST['rol']; // nuevo campo

    $sql = "SELECT * FROM usuarios WHERE usuario='$usuario'";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {

        $datos = $resultado->fetch_assoc();

        if (password_verify($password, $datos['password'])) {

            // Verificar que el rol seleccionado coincida con el rol real del usuario
            if ($datos['rol'] == $rol_seleccionado) {

                $_SESSION['id'] = $datos['id'];
                $_SESSION['nombre'] = $datos['nombre'];
                $_SESSION['rol'] = $datos['rol'];

                // Redirección según rol
                if ($datos['rol'] == 'Administrador') {
                    header("Location: index.php");
                } else {
                    header("Location: cliente.php");
                }
                exit();
            } else {
                $error = "El rol seleccionado no coincide con el usuario";
            }
        } else {
            $error = "Contraseña incorrecta";
        }
    } else {
        $error = "Usuario no encontrado";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Veterinario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a, #1e3a8a);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
        }

        .card {
            width: 450px;
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            background: white;
        }

        .card-header {
            background: #2563eb;
            color: white;
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            padding: 20px;
        }

        .btn-custom {
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 10px;
            font-weight: bold;
        }

        .btn-custom:hover {
            background: #1d4ed8;
        }

        .btn-register {
            background: #0f172a;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 10px;
            text-decoration: none;
            display: block;
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
        }

        .btn-register:hover {
            background: #020617;
            color: white;
        }

        .logo {
            text-align: center;
            font-size: 55px;
            margin-bottom: 10px;
        }

        .subtitle {
            text-align: center;
            color: gray;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="card">
    <div class="card-header">
        Sistema Veterinario
    </div>
    <div class="card-body p-4">
        <div class="logo">🐾</div>
        <div class="subtitle">Inicia sesión para continuar</div>

        <?php if (isset($error)) { ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php } ?>

        <form method="POST">
            <div class="mb-3">
                <label>Usuario</label>
                <input type="text" name="usuario" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <!-- NUEVO CAMPO: ROL -->
            <div class="mb-3">
                <label>Rol</label>
                <select name="rol" class="form-select" required>
                    <option value="">Seleccionar rol...</option>
                    <option value="Administrador">Administrador</option>
                    <option value="Cliente">Cliente</option>
                </select>
            </div>

            <div class="d-grid">
                <button class="btn btn-custom">Ingresar</button>
            </div>
        </form>

        <a href="registro.php" class="btn-register">Crear Nueva Cuenta</a>
    </div>
</div>

</body>
</html>
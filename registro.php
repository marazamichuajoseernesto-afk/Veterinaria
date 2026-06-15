<?php
include 'config/database.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];  // <-- AHORA SÍ SE RECIBE

    // 1. Verificar si el usuario ya existe
    $verificar = $conexion->query("SELECT id FROM usuarios WHERE usuario = '$usuario'");
    if($verificar->num_rows > 0){
        echo "
        <script>
            alert('Error: El usuario \"$usuario\" ya está registrado. Elige otro nombre.');
            window.history.back();
        </script>
        ";
        exit;
    }

    // 2. Insertar incluyendo el rol
    $sql = "INSERT INTO usuarios(nombre, usuario, password, rol)
            VALUES('$nombre', '$usuario', '$password', '$rol')";

    if($conexion->query($sql) === TRUE){
        echo "
        <script>
            alert('Usuario registrado correctamente');
            window.location='login.php';
        </script>
        ";
    } else {
        echo "Error: " . $conexion->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{
            background: linear-gradient(135deg,#0f172a,#1e3a8a);
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            font-family:Arial;
        }
        .card{
            width:420px;
            border:none;
            border-radius:20px;
            overflow:hidden;
            box-shadow:0 10px 30px rgba(0,0,0,.3);
        }
        .card-header{
            background:#2563eb;
            color:white;
            text-align:center;
            font-size:28px;
            font-weight:bold;
            padding:20px;
        }
        .btn-custom{
            background:#2563eb;
            color:white;
            border:none;
            border-radius:10px;
        }
        .btn-custom:hover{
            background:#1d4ed8;
        }
        .link{
            text-align:center;
            margin-top:15px;
        }
    </style>
</head>
<body>

<div class="card">
    <div class="card-header">
        Registrar Usuario
    </div>

    <div class="card-body p-4">
        <form method="POST">
            <div class="mb-3">
                <label>Nombre Completo</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Usuario</label>
                <input type="text" name="usuario" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <!-- CAMPO ROL DENTRO DEL FORMULARIO -->
            <div class="mb-3">
                <label>Rol</label>
                <select name="rol" class="form-select" required>
                    <option value="Cliente">Cliente</option>
                    <option value="Administrador">Administrador</option>
                </select>
            </div>

            <div class="d-grid">
                <button class="btn btn-custom">
                    Registrarse
                </button>
            </div>
        </form>

        <div class="link">
            <a href="login.php">
                ¿Ya tienes cuenta? Iniciar Sesión
            </a>
        </div>
    </div>
</div>

</body>
</html>
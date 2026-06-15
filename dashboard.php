<?php
session_start();

if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f4f6f9;
    font-family:Arial;
}

.box{
    width:600px;
    margin:100px auto;
    background:white;
    padding:40px;
    border-radius:20px;
    text-align:center;
    box-shadow:0 10px 20px rgba(0,0,0,.1);
}

</style>

</head>
<body>

<div class="box">

    <h1>
        Bienvenido
    </h1>

    <h3>
        <?= $_SESSION['nombre']; ?>
    </h3>

    <br>

    <a href="logout.php" class="btn btn-danger">
        Cerrar Sesión
    </a>

</div>

</body>
</html>
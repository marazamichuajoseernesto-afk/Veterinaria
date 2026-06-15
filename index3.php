<?php include 'includes/header.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Clínica Veterinaria La Unión</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{

    /* IMAGEN DE FONDO */
    background:
    linear-gradient(rgba(2,6,23,0.8),
    rgba(2,6,23,0.8)),
    url('https://images.unsplash.com/photo-1583511655857-d19b40a7a54e?q=80&w=1600&auto=format&fit=crop');

    background-size:cover;
    background-position:center;
    background-attachment:fixed;

    overflow-x:hidden;
}



/* CAJA PRINCIPAL */
.hero-box{

    background:rgba(15,23,42,0.85);

    backdrop-filter:blur(12px);

    border:1px solid rgba(255,255,255,0.1);

    border-radius:35px;

    padding:70px;

    max-width:1200px;

    width:100%;

    box-shadow:0 15px 40px rgba(0,0,0,0.5);

    animation:fadeIn 1.5s ease;
}

/* CONTENIDO */
.hero-content{
    text-align:center;
    color:white;
}

/* TITULO */
.hero-content h1{

    font-size:5rem;

    font-weight:700;

    margin-bottom:25px;

    line-height:1.1;

    color:#ffffff;

    text-shadow:0 5px 15px rgba(0,0,0,0.5);
}

/* TEXTO */
.hero-content p{

    font-size:1.3rem;

    color:#f1f5f9;

    max-width:850px;

    margin:auto;

    margin-bottom:40px;

    line-height:1.8;
}

/* BOTON */
.btn-hero{

    background:linear-gradient(135deg,#2563eb,#1d4ed8);

    color:white;

    padding:18px 45px;

    border-radius:50px;

    text-decoration:none;

    font-size:1.2rem;

    font-weight:600;

    transition:0.4s;

    display:inline-block;

    box-shadow:0 10px 30px rgba(37,99,235,0.5);
}

.btn-hero:hover{

    transform:translateY(-5px) scale(1.05);

    color:white;

    background:linear-gradient(135deg,#1d4ed8,#2563eb);
}

/* TITULO SECCION */
.titulo-seccion{

    text-align:center;

    color:white;

    font-size:3rem;

    font-weight:bold;

    margin-bottom:50px;

    text-shadow:0 5px 15px rgba(0,0,0,0.5);
}

/* CONTENEDOR */
.container-custom{
    padding:80px 40px;
}

/* GRID */
.menu{

    display:grid;

    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));

    gap:30px;
}

/* TARJETAS */
.card-menu{

    background:rgba(255,255,255,0.08);

    backdrop-filter:blur(10px);

    border:1px solid rgba(255,255,255,0.1);

    border-radius:25px;

    padding:40px;

    text-align:center;

    transition:0.4s;

    text-decoration:none;

    color:white;

    box-shadow:0 10px 30px rgba(0,0,0,0.3);
}

.card-menu:hover{

    transform:translateY(-10px) scale(1.03);

    background:#2563eb;

    color:white;
}

.card-menu i{

    font-size:60px;

    margin-bottom:20px;
}

.card-menu h3{

    font-size:1.5rem;

    font-weight:600;
}

/* INFORMACION */
.info-box{

    margin-top:80px;

    background:linear-gradient(135deg,#2563eb,#1e40af);

    border-radius:30px;

    padding:60px;

    text-align:center;

    color:white;

    box-shadow:0 10px 30px rgba(37,99,235,0.4);
}

.info-box h2{

    font-size:2.3rem;

    margin-bottom:20px;
}

.info-box p{

    font-size:1.2rem;

    color:#dbeafe;

    line-height:1.8;
}

/* FOOTER */
.footer{

    background:rgba(2,6,23,0.95);

    padding:50px 20px;

    text-align:center;

    color:#e2e8f0;

    border-top:1px solid rgba(255,255,255,0.1);

    margin-top:50px;
}

.footer h5{

    font-weight:bold;

    color:white;

    font-size:1.8rem;
}

.footer p{

    margin:8px 0;

    color:#cbd5e1;

    font-size:1.1rem;
}

/* ANIMACION */
@keyframes fadeIn{

    from{
        opacity:0;
        transform:translateY(30px);
    }

    to{
        opacity:1;
        transform:translateY(0);
    }
}

/* RESPONSIVE */
@media(max-width:768px){

    .hero-box{
        padding:40px 25px;
    }

    .hero-content h1{
        font-size:2.8rem;
    }

    .hero-content p{
        font-size:1rem;
    }

    .titulo-seccion{
        font-size:2rem;
    }

}

</style>

</head>

<body>

<!-- HERO -->
<section class="hero">

    <div class="hero-box">

        <div class="hero-content">

            <h1>
                🐾 Clínica <br>
                Veterinaria La Unión
            </h1>

            <p>
                Sistema moderno e inteligente para la gestión completa
                de clínicas veterinarias. Controla clientes, mascotas,
                citas, historiales clínicos, inventario y facturación
                desde una sola plataforma.
            </p>

            <a href="pages/clientes.php"
               class="btn-hero">

                <i class="bi bi-rocket-takeoff-fill"></i>
                Comenzar Ahora

            </a>

        </div>

    </div>

</section>

<!-- MODULOS -->
<div class="container-custom">

    <h2 class="titulo-seccion">
        🚀 Módulos Inteligentes del Sistema
    </h2>

    <div class="menu">

        <a href="pages/clientes.php" class="card-menu">
            <i class="bi bi-people-fill"></i>
            <h3>Clientes</h3>
        </a>

        <a href="pages/mascotas.php" class="card-menu">
            <i class="bi bi-heart-fill"></i>
            <h3>Mascotas</h3>
        </a>

        <a href="pages/veterinario.php" class="card-menu">
            <i class="bi bi-person-badge-fill"></i>
            <h3>Veterinarios</h3>
        </a>

        <a href="pages/citas.php" class="card-menu">
            <i class="bi bi-calendar2-check-fill"></i>
            <h3>Citas</h3>
        </a>

        <a href="pages/historial.php" class="card-menu">
            <i class="bi bi-clipboard2-pulse-fill"></i>
            <h3>Historial Clínico</h3>
        </a>

        <a href="pages/inventario.php" class="card-menu">
            <i class="bi bi-capsule-pill"></i>
            <h3>Inventario</h3>
        </a>

        <a href="pages/facturacion.php" class="card-menu">
            <i class="bi bi-receipt-cutoff"></i>
            <h3>Facturación</h3>
        </a>
        
   

    </div>

    <!-- INFORMACION -->
    <div class="info-box">

        <h2>
            🏥 Tecnología Veterinaria Moderna
        </h2>

        <p>
            Optimiza procesos, reduce errores y mejora la atención
            médica de tus mascotas con una interfaz elegante,
            rápida y totalmente profesional.
        </p>

    </div>

</div>

<!-- FOOTER -->
<div class="footer">

    <h5 class="mb-3">
        🏥 Clínica Veterinaria La Unión
    </h5>

    <p>
        📍 Dirección:
        LA PAZ - CEMENTERIO GENERAL
    </p>

    <p>
        📞 Teléfono:
        76543212
    </p>

    <p class="mt-3 text-secondary">
        © 2026 Sistema Web Veterinario Moderno
    </p>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php include 'includes/footer.php'; ?>
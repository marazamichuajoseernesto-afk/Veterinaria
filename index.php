<?php include 'includes/header.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clínica Veterinaria La Unión | Escritorio Profesional</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-image: linear-gradient(125deg, rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.85)), 
                              url('https://images.unsplash.com/photo-1583511655857-d19b40a7a54e?q=80&w=1600&auto=format&fit=crop');
            background-size: cover;
            background-position: center 30%;
            background-attachment: fixed;
            background-repeat: no-repeat;
            overflow-x: hidden;
        }

        /* ===== TOP BAR ===== */
        .desktop-top-bar {
            background: #0f172a;
            border-bottom: 1px solid #1e293b;
            padding: 12px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .logo-area h2 {
            font-size: 1.6rem;
            font-weight: 700;
            margin: 0;
            color: white;
        }

        .logo-area span {
            font-size: 0.85rem;
            color: #94a3b8;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-badge {
            background: #1e293b;
            padding: 8px 18px;
            border-radius: 40px;
            font-weight: 500;
            color: #e2e8f0;
        }

        .btn-dashboard {
            background: #3b82f6;
            color: white;
            border-radius: 30px;
            padding: 8px 20px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.2s;
        }

        .btn-dashboard:hover {
            background: #2563eb;
            color: white;
        }

        /* ===== CONTENEDOR PRINCIPAL ===== */
        .dashboard-main {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 30px;
        }

        /* ===== BLOQUE DE BIENVENIDA CON FONDO OSCURO ===== */
        .welcome-wrapper {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(12px);
            border-radius: 32px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            margin-bottom: 50px;
            overflow: hidden;
        }

        .welcome-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            padding: 40px;
            gap: 30px;
        }

        .welcome-text {
            flex: 1.2;
        }

        .welcome-text h1 {
            font-size: 2.2rem;
            font-weight: 800;
            background: linear-gradient(135deg, #ffffff, #bfdbfe);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 16px;
        }

        .welcome-text p {
            color: #e2e8f0;
            font-size: 1rem;
            line-height: 1.5;
            margin-bottom: 28px;
        }

        .btn-hero {
            background: linear-gradient(95deg, #2563eb, #1e40af);
            color: white;
            padding: 12px 32px;
            border-radius: 40px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s;
            border: none;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
        }

        .btn-hero:hover {
            transform: translateY(-3px);
            background: #1d4ed8;
            color: white;
        }

        .welcome-image {
            flex: 0.8;
            text-align: center;
        }

        .welcome-image img {
            max-width: 100%;
            border-radius: 24px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            border: 2px solid rgba(255,255,255,0.2);
        }

        /* ===== TÍTULO DE SECCIÓN CENTRADO ===== */
        .title-wrapper {
            text-align: center;
            margin: 20px 0 35px;
        }

        .titulo-seccion {
            text-align: center;
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(120deg, #bfdbfe, #60a5fa);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            display: inline-block;
            padding: 8px 30px;
            letter-spacing: -0.5px;
        }

        /* ===== MÓDULOS (4 COLUMNAS) ===== */
        .menu {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            margin-bottom: 55px;
        }

        .card-menu {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 28px;
            padding: 28px 16px;
            text-align: center;
            transition: all 0.25s ease;
            text-decoration: none;
            color: #0f172a;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255,255,255,0.3);
        }

        .card-menu:hover {
            transform: translateY(-6px);
            background: #ffffff;
            border-color: #3b82f6;
            box-shadow: 0 20px 30px -12px rgba(0, 0, 0, 0.25);
        }

        .card-menu i {
            font-size: 54px;
            margin-bottom: 18px;
            color: #2563eb;
        }

        .card-menu h3 {
            font-size: 1.35rem;
            font-weight: 600;
            margin: 0;
            color: #0f172a;
        }

        /* ===== INFO BOX CON FONDO AZUL (COMO EL BOTÓN) ===== */
        .info-box {
            background: linear-gradient(95deg, #2563eb, #1e40af);
            border-radius: 32px;
            padding: 45px 40px;
            text-align: center;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            margin: 20px 0 40px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .info-box h2 {
            font-size: 1.9rem;
            font-weight: 700;
            color: white;
            margin-bottom: 20px;
        }

        .info-box p {
            font-size: 1.05rem;
            color: #f1f5f9;
            max-width: 700px;
            margin: 0 auto;
        }

        /* ===== FOOTER ===== */
        .footer {
            background: #0f172a;
            padding: 40px 20px;
            text-align: center;
            color: #e2e8f0;
            border-top: 1px solid #1e293b;
            margin-top: 45px;
        }

        .footer h5 {
            font-weight: 700;
            color: white;
            font-size: 1.5rem;
        }

        .footer p {
            margin: 8px 0;
            color: #94a3b8;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .menu {
                grid-template-columns: repeat(2, 1fr);
            }
            .welcome-card {
                flex-direction: column;
                text-align: center;
            }
            .welcome-text {
                text-align: center;
            }
            .welcome-image {
                max-width: 280px;
                margin: 0 auto;
            }
        }

        @media (max-width: 576px) {
            .menu {
                grid-template-columns: 1fr;
            }
            .titulo-seccion {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

<!-- BARRA SUPERIOR -->
<div class="desktop-top-bar">
    <div class="logo-area">
        <h2>🐾 Clínica Veterinaria La Unión</h2>
        <span>Sistema Profesional de Gestión</span>
    </div>
    <div class="user-info">
        <div class="user-badge">
            <i class="bi bi-person-circle"></i> Veterinario Principal
        </div>
        <a href="pages/clientes.php" class="btn-dashboard">
            <i class="bi bi-grid-3x3-gap-fill"></i> Escritorio
        </a>
         <a href="pages/reporte_facturas.php" class="btn-dashboard" style="background: #10b981;">
        <i class="bi bi-file-earmark-text-fill"></i> Reportes
    </a>
    </div>
</div>
<a href="listar_usuarios.php" class="btn btn-primary">
    <i class="bi bi-people-fill"></i> Ver Usuarios (Admin/Clientes)
</a>

<main class="dashboard-main">
    <!-- BLOQUE DE BIENVENIDA CON FONDO OSCURO -->
    <div class="welcome-wrapper">
        <div class="welcome-card">
            <div class="welcome-text">
                <h1>🐾 Clínica Veterinaria La Unión</h1>
                <p>
                    Sistema moderno e inteligente para la gestión completa de clínicas veterinarias. 
                    Controla clientes, mascotas, citas, historiales clínicos, inventario, pagos y facturación 
                    desde una sola plataforma.
                </p>
                <a href="pages/clientes.php" class="btn-hero">
                    <i class="bi bi-rocket-takeoff-fill"></i> Comenzar Ahora
                </a>
            </div>
            <div class="welcome-image">
                <img src="https://images.unsplash.com/photo-1543466835-00a7907e9de1?q=80&w=300&auto=format&fit=crop" 
                     alt="Perrito feliz" 
                     style="border-radius: 28px; max-width: 260px;">
            </div>
        </div>
    </div>

    <!-- TÍTULO DE MÓDULOS CENTRADO -->
    <div class="title-wrapper">
        <h2 class="titulo-seccion">
            🚀 Módulos Inteligentes del Sistema
        </h2>
    </div>

    <!-- MÓDULOS (8, en 4 columnas) -->
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

        <a href="pages/pagos.php" class="card-menu">
            <i class="bi bi-credit-card-2-front-fill"></i>
            <h3>Pagos</h3>
        </a>
       
        <a href="pages/facturacion.php" class="card-menu">
            <i class="bi bi-receipt-cutoff"></i>
            <h3>Facturación</h3>
        </a>
    </div>

    <!-- INFO BOX CON FONDO AZUL (cambiado) -->
    <div class="info-box">
        <h2>🏥 Tecnología Veterinaria Moderna</h2>
        <p>
            Optimiza procesos, reduce errores y mejora la atención médica de tus mascotas 
            con una interfaz elegante, rápida y totalmente profesional.
        </p>
    </div>
</main>

<!-- FOOTER -->
<div class="footer">
    <h5 class="mb-3">🏥 Clínica Veterinaria La Unión</h5>
    <p>📍 Dirección: LA PAZ - CEMENTERIO GENERAL</p>
    <p>📞 Teléfono: 76543212</p>
    <p class="mt-3 text-secondary">© 2026 Sistema Web Veterinario Moderno</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include 'includes/footer.php'; ?>
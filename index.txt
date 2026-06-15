<?php include 'includes/header.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VetLaUnión | Gestión Veterinaria Moderna</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts (Poppins + Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f4f9fe;
            overflow-x: hidden;
            color: #1a2c3e;
        }

        h1, h2, h3, h4, .navbar-brand, .btn {
            font-family: 'Poppins', sans-serif;
        }

        /* NAVBAR MODERNA */
        .navbar {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(8px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            padding: 0.8rem 2rem;
            transition: all 0.3s;
        }
        .navbar-brand {
            font-weight: 800;
            font-size: 1.7rem;
            background: linear-gradient(135deg, #1f7b4d, #38a169);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: -0.5px;
        }
        .navbar-brand i {
            background: none;
            color: #2c7a4d;
            margin-right: 6px;
        }
        .nav-link {
            font-weight: 500;
            color: #2d3e50 !important;
            margin: 0 8px;
            transition: 0.2s;
        }
        .nav-link:hover {
            color: #38a169 !important;
            transform: translateY(-2px);
        }
        .btn-outline-success {
            border-color: #38a169;
            color: #38a169;
            border-radius: 40px;
            padding: 6px 18px;
            font-weight: 500;
        }
        .btn-outline-success:hover {
            background: #38a169;
            border-color: #38a169;
            color: white;
        }

        /* HERO MEJORADO */
        .hero-section {
            padding: 5rem 2rem 4rem;
            background: linear-gradient(120deg, #eef7f0 0%, #ffffff 100%);
            position: relative;
            overflow: hidden;
        }
        .hero-title {
            font-size: 3.2rem;
            font-weight: 800;
            color: #1a4d2e;
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }
        .hero-title span {
            background: linear-gradient(130deg, #2b7a4b, #5cb85c);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .hero-text {
            font-size: 1.1rem;
            color: #2c5a3b;
            margin-bottom: 2rem;
            max-width: 90%;
        }
        .btn-primary-custom {
            background: linear-gradient(95deg, #2c7a4d, #3b9b62);
            border: none;
            padding: 12px 32px;
            border-radius: 50px;
            font-weight: 600;
            color: white;
            transition: 0.3s;
            box-shadow: 0 8px 18px rgba(44, 122, 77, 0.3);
        }
        .btn-primary-custom:hover {
            transform: scale(1.03);
            background: linear-gradient(95deg, #246e43, #2e8b57);
            box-shadow: 0 12px 22px rgba(44, 122, 77, 0.4);
            color: white;
        }
        .hero-image {
            animation: floatImage 3s ease-in-out infinite;
        }
        @keyframes floatImage {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }

        /* STATS */
        .stats-section {
            background: white;
            padding: 2.5rem;
            border-radius: 60px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
            margin-top: -40px;
            position: relative;
            z-index: 5;
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: #2c7a4d;
        }

        /* SECCIÓN MÓDULOS (TARJETAS MEJORADAS) */
        .section-title {
            font-size: 2.4rem;
            font-weight: 700;
            color: #1a3a2a;
            margin-bottom: 1rem;
        }
        .section-sub {
            color: #4a6a5a;
            margin-bottom: 3rem;
        }
        .module-card {
            background: white;
            border-radius: 32px;
            padding: 1.8rem 1.2rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
            border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 12px 24px -12px rgba(0, 0, 0, 0.08);
        }
        .module-card:hover {
            transform: translateY(-8px);
            background: #ffffff;
            border-color: #c6f0d2;
            box-shadow: 0 25px 35px -12px rgba(44, 122, 77, 0.2);
        }
        .card-icon {
            font-size: 3rem;
            background: #eef7f0;
            width: 70px;
            height: 70px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 30px;
            margin-bottom: 1.5rem;
            transition: 0.2s;
        }
        .module-card:hover .card-icon {
            background: #2c7a4d;
            color: white;
        }
        .module-card h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e3a2f;
        }
        .module-card p {
            color: #5a6e64;
            font-size: 0.9rem;
        }

        /* FEATURES GRID */
        .feature-icon {
            font-size: 2rem;
            background: #e7f3ee;
            width: 55px;
            height: 55px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #2c7a4d;
        }

        /* TESTIMONIAL */
        .testimonial-card {
            background: white;
            border-radius: 40px;
            padding: 2rem;
            box-shadow: 0 20px 35px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2efe8;
        }

        /* FOOTER MODERNO */
        .footer-modern {
            background: #0f2a1f;
            color: #d4e6da;
            padding: 3rem 2rem 1.5rem;
            margin-top: 4rem;
            border-radius: 40px 40px 0 0;
        }
        .footer-modern a {
            color: #c0e0ce;
            text-decoration: none;
            transition: 0.2s;
        }
        .footer-modern a:hover {
            color: white;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.2rem;
            }
            .stats-section {
                margin-top: 0;
            }
            .navbar {
                padding: 0.6rem 1rem;
            }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">
            <i class="bi bi-heart-pulse-fill"></i> Vet<span style="color:#2c7a4d;">LaUnión</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="#">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="#modulos">Módulos</a></li>
                <li class="nav-item"><a class="nav-link" href="#features">Ventajas</a></li>
                <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>
            </ul>
            <a href="pages/clientes.php" class="btn btn-outline-success ms-lg-3 mt-2 mt-lg-0">Acceder <i class="bi bi-arrow-right-short"></i></a>
        </div>
    </div>
</nav>

<!-- HERO SECTION -->
<section class="hero-section" style="padding-top: 120px;">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h1 class="hero-title">Gestión <span>veterinaria</span> inteligente para clínicas del futuro</h1>
                <p class="hero-text">Controla pacientes, historiales clínicos, inventario y facturación con una plataforma ágil, moderna y segura. Todo en un solo ecosistema.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="pages/clientes.php" class="btn btn-primary-custom"><i class="bi bi-rocket-takeoff-fill me-2"></i>Comenzar ahora</a>
                    <a href="#modulos" class="btn btn-outline-secondary rounded-pill px-4">Explorar módulos <i class="bi bi-chevron-down"></i></a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="https://images.unsplash.com/photo-1583337130417-3346a1be7dee?w=500&auto=format" alt="Veterinaria" class="img-fluid hero-image rounded-4 shadow-lg" style="max-height: 380px; object-fit: cover; width: 100%;">
            </div>
        </div>
    </div>
</section>

<!-- STATS CARD -->
<div class="container position-relative">
    <div class="stats-section row text-center gy-4">
        <div class="col-md-4">
            <div class="stat-number">2.5k+</div>
            <p class="mb-0 text-muted">Mascotas registradas</p>
        </div>
        <div class="col-md-4">
            <div class="stat-number">98%</div>
            <p class="mb-0 text-muted">Satisfacción del cliente</p>
        </div>
        <div class="col-md-4">
            <div class="stat-number">24/7</div>
            <p class="mb-0 text-muted">Soporte técnico</p>
        </div>
    </div>
</div>

<!-- MÓDULOS PRINCIPALES -->
<div class="container py-5" id="modulos">
    <div class="text-center">
        <h2 class="section-title">Módulos inteligentes del sistema</h2>
        <p class="section-sub">Administración completa con diseño fluido y adaptado a tu clínica</p>
    </div>
    <div class="row g-4">
        <div class="col-md-6 col-lg-3">
            <a href="pages/clientes.php" class="module-card text-center">
                <div class="card-icon"><i class="bi bi-people-fill fs-1"></i></div>
                <h3>Clientes</h3>
                <p>Gestión de tutores, datos de contacto, historial y más</p>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="pages/mascotas.php" class="module-card text-center">
                <div class="card-icon"><i class="bi bi-heart-fill fs-1"></i></div>
                <h3>Mascotas</h3>
                <p>Registro completo, especie, raza y fichas médicas</p>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="pages/veterinario.php" class="module-card text-center">
                <div class="card-icon"><i class="bi bi-person-badge-fill fs-1"></i></div>
                <h3>Veterinarios</h3>
                <p>Equipo profesional, especialidades y horarios</p>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="pages/citas.php" class="module-card text-center">
                <div class="card-icon"><i class="bi bi-calendar2-check-fill fs-1"></i></div>
                <h3>Citas</h3>
                <p>Agenda digital, recordatorios y seguimiento</p>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="pages/historial.php" class="module-card text-center">
                <div class="card-icon"><i class="bi bi-clipboard2-pulse-fill fs-1"></i></div>
                <h3>Historial Clínico</h3>
                <p>Consultas, diagnósticos, vacunas y tratamientos</p>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="pages/inventario.php" class="module-card text-center">
                <div class="card-icon"><i class="bi bi-capsule-pill fs-1"></i></div>
                <h3>Inventario</h3>
                <p>Medicamentos, insumos y control de stock</p>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <a href="pages/facturacion.php" class="module-card text-center">
                <div class="card-icon"><i class="bi bi-receipt-cutoff fs-1"></i></div>
                <h3>Facturación</h3>
                <p>Presupuestos, cobros y reportes financieros</p>
            </a>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="module-card text-center bg-light">
                <div class="card-icon"><i class="bi bi-graph-up fs-1"></i></div>
                <h3>Reportes</h3>
                <p>Estadísticas, ingresos y rendimiento clínico</p>
            </div>
        </div>
    </div>
</div>

<!-- FEATURES & BENEFICIOS -->
<div class="container py-4" id="features">
    <div class="row g-5 align-items-center">
        <div class="col-lg-6">
            <img src="https://images.unsplash.com/photo-1628009368231-7bb7cfb0f2d5?w=600&auto=format" class="img-fluid rounded-4 shadow" alt="Veterinaria digital">
        </div>
        <div class="col-lg-6">
            <h2 class="fw-bold" style="color: #1f543d;">Tecnología que transforma la atención veterinaria</h2>
            <div class="row mt-4 g-4">
                <div class="col-md-6">
                    <div class="feature-icon mb-3"><i class="bi bi-cloud-check"></i></div>
                    <h5 class="fw-semibold">Datos en la nube</h5>
                    <p class="text-muted">Accede desde cualquier lugar, respaldos automáticos.</p>
                </div>
                <div class="col-md-6">
                    <div class="feature-icon mb-3"><i class="bi bi-shield-lock"></i></div>
                    <h5 class="fw-semibold">Seguridad total</h5>
                    <p class="text-muted">Encriptación de datos y roles de usuario.</p>
                </div>
                <div class="col-md-6">
                    <div class="feature-icon mb-3"><i class="bi bi-bell"></i></div>
                    <h5 class="fw-semibold">Alertas automáticas</h5>
                    <p class="text-muted">Recordatorio de vacunas y próximas citas.</p>
                </div>
                <div class="col-md-6">
                    <div class="feature-icon mb-3"><i class="bi bi-file-earmark-medical"></i></div>
                    <h5 class="fw-semibold">Historial unificado</h5>
                    <p class="text-muted">Expediente digital completo por mascota.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TESTIMONIAL -->
<div class="container my-5">
    <div class="testimonial-card text-center">
        <i class="bi bi-quote fs-1 text-success opacity-50"></i>
        <p class="fs-5 fst-italic mt-2">"Este sistema revolucionó la organización de nuestra clínica. Ahorramos horas administrativas y nuestros clientes están más contentos."</p>
        <div class="d-flex align-items-center justify-content-center gap-3 mt-3">
            <img src="https://randomuser.me/api/portraits/women/68.jpg" width="50" class="rounded-circle">
            <div><strong>Dra. Mariana López</strong><br><small>Directora Veterinaria</small></div>
        </div>
    </div>
</div>

<!-- CONTACTO / INFO -->
<div class="container" id="contacto">
    <div class="row g-4 bg-white p-4 rounded-4 shadow-sm">
        <div class="col-md-6">
            <h4><i class="bi bi-geo-alt-fill text-success"></i> Nuestra ubicación</h4>
            <p class="mt-2">📍 LA PAZ - CEMENTERIO GENERAL<br>Zona Sur, Edificio Vet Plaza</p>
            <h4><i class="bi bi-telephone-fill text-success"></i> Contacto directo</h4>
            <p>📞 76543212 · ✉️ contacto@vetlaunion.bo</p>
        </div>
        <div class="col-md-6">
            <h4><i class="bi bi-clock-history"></i> Horario de atención</h4>
            <p>Lunes a Viernes: 8:00 - 19:00<br>Sábados: 9:00 - 14:00<br>Emergencias 24/7</p>
        </div>
    </div>
</div>

<!-- FOOTER MODERNO -->
<div class="footer-modern">
    <div class="container">
        <div class="row gy-4">
            <div class="col-md-5">
                <h4 class="fw-bold text-white"><i class="bi bi-heart-pulse"></i> Clínica Veterinaria La Unión</h4>
                <p class="mt-2">Innovación y bienestar animal, gestión inteligente para profesionales comprometidos.</p>
            </div>
            <div class="col-md-3">
                <h5>Enlaces rápidos</h5>
                <ul class="list-unstyled">
                    <li><a href="#modulos">Módulos</a></li>
                    <li><a href="pages/clientes.php">Panel Clientes</a></li>
                    <li><a href="#">Políticas</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Redes sociales</h5>
                <div class="d-flex gap-3 fs-4">
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-instagram"></i></a>
                    <a href="#"><i class="bi bi-whatsapp"></i></a>
                </div>
                <p class="mt-3 small">© 2026 Sistema Web Veterinario Moderno — Todos los derechos reservados.</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php include 'includes/footer.php'; ?>
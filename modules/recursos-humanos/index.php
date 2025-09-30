<?php
session_start();

// Verificar autenticación
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: ../../index.php');
    exit();
}

$user_name = $_SESSION['user_name'] ?? 'Usuario';
$username = $_SESSION['username'] ?? '';

// Datos de ejemplo para demostración
$stats = [
    'total_empleados' => 152,
    'nuevos_mes' => 12
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recursos Humanos - Sistema de Gestión</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #28a745;
            --secondary-color: #20c997;
            --sidebar-width: 280px;
            --header-height: 70px;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        /* Header */
        .main-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--header-height);
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            z-index: 1030;
        }
        
        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            height: 100%;
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .menu-toggle {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            padding: 0.5rem;
            border-radius: 8px;
            transition: background 0.3s ease;
        }
        
        .menu-toggle:hover {
            background: rgba(255,255,255,0.1);
        }
        
        .logo-brand {
            color: white;
            text-decoration: none;
            font-size: 1.3rem;
            font-weight: 600;
        }
        
        .breadcrumb-nav {
            color: rgba(255,255,255,0.8);
            font-size: 0.9rem;
        }
        
        .breadcrumb-nav a {
            color: rgba(255,255,255,0.9);
            text-decoration: none;
        }
        
        .user-dropdown {
            background: rgba(255,255,255,0.1);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: var(--header-height);
            left: -280px;
            width: var(--sidebar-width);
            height: calc(100vh - var(--header-height));
            background: white;
            box-shadow: 2px 0 20px rgba(0,0,0,0.1);
            transition: left 0.3s ease;
            z-index: 1020;
            overflow-y: auto;
        }
        
        .sidebar.active {
            left: 0;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 1rem 0;
            margin: 0;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 1rem 2rem;
            color: #555;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: #f8f9fa;
            color: var(--primary-color);
            border-left-color: var(--primary-color);
        }
        
        .sidebar-menu i {
            width: 20px;
            margin-right: 1rem;
        }
        
        /* Contenido principal */
        .main-content {
            margin-top: var(--header-height);
            padding: 2rem;
            min-height: calc(100vh - var(--header-height));
            transition: margin-left 0.3s ease;
        }
        
        .page-header {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
            color: white;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        
        .content-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .card-header-custom {
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1.5rem 2rem;
            border: none;
        }
        
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            padding: 2rem;
        }
        
        .action-btn {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 1rem;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
        }
        
        .action-btn:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }
        
        .action-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--primary-color);
            color: white;
            font-size: 1.2rem;
        }
        
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1010;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            border-radius: 15px;
        }
        
        /* Responsive */
        @media (min-width: 992px) {
            .sidebar {
                left: 0;
            }
            
            .main-content {
                margin-left: var(--sidebar-width);
            }
            
            .menu-toggle {
                display: none;
            }
        }
        
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .quick-actions {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <div class="header-content">
            <div class="header-left">
                <button class="menu-toggle d-lg-none" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <a href="../../dashboard.php" class="logo-brand">
                    <i class="fas fa-users me-2"></i>
                    Recursos Humanos
                </a>
                <div class="breadcrumb-nav d-none d-md-block">
                    <a href="../../dashboard.php">Dashboard</a> / Recursos Humanos
                </div>
            </div>
            
            <div class="user-menu">
                <div class="dropdown">
                    <button class="user-dropdown dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i>
                        <span class="d-none d-md-inline"><?php echo htmlspecialchars($user_name); ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Mi Perfil</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Configuración</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="../../logout.php"><i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <ul class="sidebar-menu">
            <li><a href="../../dashboard.php"><i class="fas fa-home"></i>Dashboard</a></li>
            <li><a href="#" class="active"><i class="fas fa-users"></i>Recursos Humanos</a></li>
            <li><a href="../fichas/index.php"><i class="fas fa-id-card"></i>Fichas</a></li>
            <li><a href="../documentos/index.php"><i class="fas fa-file-alt"></i>Documentos</a></li>
            <li><a href="../giftcard/index.php"><i class="fas fa-gift"></i>Gift Cards</a></li>
            <li><a href="../finanzas/index.php"><i class="fas fa-chart-line"></i>Finanzas</a></li>
            <li><a href="../servicios/index.php"><i class="fas fa-cogs"></i>Servicios</a></li>
            <li><hr class="my-2"></li>
            <li><a href="#"><i class="fas fa-chart-bar"></i>Reportes</a></li>
            <li><a href="#"><i class="fas fa-cog"></i>Configuración</a></li>
        </ul>
    </nav>
    
    <!-- Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
    
    <!-- Contenido Principal -->
    <main class="main-content">
        <!-- Header de Página -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="h3 mb-2">
                        <i class="fas fa-users text-success me-2"></i>
                        Recursos Humanos
                    </h1>
                    <p class="text-muted mb-0">Gestión integral de personal y recursos humanos</p>
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Nuevo Empleado
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Cards de Estadísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: #28a745;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number"><?php echo number_format($stats['total_empleados']); ?></div>
                <div class="stat-label">Total Empleados</div>
            </div>           
            <div class="stat-card">
                <div class="stat-icon" style="background: #dc3545;">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="stat-number"><?php echo $stats['nuevos_mes']; ?></div>
                <div class="stat-label">Nuevos Este Mes</div>
            </div>
        </div>
        
        <!-- Acciones Rápidas -->
        <div class="content-card">
            <div class="card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Acciones Rápidas
                </h5>
            </div>
            <div class="quick-actions">
                <a href="#" class="action-btn">
                    <div class="action-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div>
                        <div class="fw-bold">Registrar Empleado</div>
                        <small class="text-muted">Agregar nuevo personal</small>
                    </div>
                </a>
                
                <a href="#" class="action-btn">
                    <div class="action-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div>
                        <div class="fw-bold">Gestionar Nóminas</div>
                        <small class="text-muted">Procesar pagos</small>
                    </div>
                </a>
                
                <a href="#" class="action-btn">
                    <div class="action-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <div class="fw-bold">Control de Asistencia</div>
                        <small class="text-muted">Registrar horarios</small>
                    </div>
                </a>
                
                <a href="#" class="action-btn">
                    <div class="action-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div>
                        <div class="fw-bold">Reportes RRHH</div>
                        <small class="text-muted">Análisis y estadísticas</small>
                    </div>
                </a>
                
                <a href="#" class="action-btn">
                    <div class="action-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div>
                        <div class="fw-bold">Capacitaciones</div>
                        <small class="text-muted">Programar entrenamientos</small>
                    </div>
                </a>
                
                <a href="#" class="action-btn">
                    <div class="action-icon">
                        <i class="fas fa-file-contract"></i>
                    </div>
                    <div>
                        <div class="fw-bold">Contratos</div>
                        <small class="text-muted">Gestionar documentos</small>
                    </div>
                </a>
            </div>
        </div>
        
        <!-- Información del Módulo -->
        <div class="content-card">
            <div class="card-header-custom">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Información del Módulo
                </h5>
            </div>
            <div class="p-4">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Funcionalidades Principales:</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Gestión de empleados</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Control de nóminas</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Registro de asistencia</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Evaluaciones de desempeño</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Gestión de vacaciones</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3">Próximas Características:</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-clock text-warning me-2"></i>Reclutamiento digital</li>
                            <li class="mb-2"><i class="fas fa-clock text-warning me-2"></i>Portal del empleado</li>
                            <li class="mb-2"><i class="fas fa-clock text-warning me-2"></i>Integración con contabilidad</li>
                            <li class="mb-2"><i class="fas fa-clock text-warning me-2"></i>Reportes avanzados</li>
                            <li class="mb-2"><i class="fas fa-clock text-warning me-2"></i>Notificaciones automáticas</li>
                        </ul>
                    </div>
                </div>
                
                <div class="alert alert-info mt-4">
                    <i class="fas fa-lightbulb me-2"></i>
                    <strong>Nota:</strong> Este es un módulo de demostración. En un sistema real, 
                    aquí encontrarías todas las funcionalidades completas para la gestión de recursos humanos.
                </div>
            </div>
        </div>
    </main>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle Sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }
        
        // Cerrar sidebar al hacer clic en enlaces (móvil)
        document.querySelectorAll('.sidebar-menu a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) {
                    toggleSidebar();
                }
            });
        });
        
        // Animaciones en las cards
        document.querySelectorAll('.stat-card, .action-btn').forEach(element => {
            element.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });
            
            element.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>
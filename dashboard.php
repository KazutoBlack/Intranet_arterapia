<?php
session_start();

// Verificar autenticación
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: index.php');
    exit();
}

$user_name = $_SESSION['user_name'] ?? 'Usuario';
$username = $_SESSION['username'] ?? '';
$user_role = $_SESSION['user_role'] ?? 'user';

// Definir módulos del sistema
$modules = [
    'recursos-humanos' => [
        'name' => 'Recursos Humanos',
        'icon' => 'fas fa-users',
        'color' => '#28a745',
        'description' => 'Gestión de personal, nóminas y recursos humanos',
        'stats' => ['152 Empleados'],
        'url' => 'modules/recursos-humanos/index.php'
    ],
    'fichas' => [
        'name' => 'Fichas',
        'icon' => 'fas fa-id-card',
        'color' => '#007bff',
        'description' => 'Gestión de fichas de pacientes y registros médicos',
        'stats' => ['324 Fichas', '89 Activos', '12 Nuevas Hoy'],
        'url' => 'modules/fichas/index.php'
    ],
    'documentos' => [
        'name' => 'Administración de Documentos',
        'icon' => 'fas fa-file-alt',
        'color' => '#fd7e14',
        'description' => 'Gestión documental centralizada y archivo digital',
        'stats' => ['1,254 Documentos'],
        'url' => 'modules/documentos/index.php'
    ],
    'giftcard' => [
        'name' => 'Gift Cards',
        'icon' => 'fas fa-gift',
        'color' => '#dc3545',
        'description' => 'Sistema de tarjetas regalo y promociones',
        'stats' => ['89 Activas', '$12,450 Total', '34 Este Mes'],
        'url' => 'modules/giftcard/index.php'
    ],
    'finanzas' => [
        'name' => 'Finanzas',
        'icon' => 'fas fa-chart-line',
        'color' => '#6f42c1',
        'description' => 'Control financiero, reportes y análisis económico',
        'stats' => ['$89,320 Ingresos', '$23,450 Gastos', '+15.2% Mes'],
        'url' => 'modules/finanzas/index.php'
    ],
    'servicios' => [
        'name' => 'Servicios',
        'icon' => 'fas fa-cogs',
        'color' => '#6c757d',
        'description' => 'Gestión de servicios empresariales y reservas',
        'stats' => ['23 Servicios', '145 Reservas', '98% Satisfacción'],
        'url' => 'modules/servicios/index.php'
    ]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Gestión</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --sidebar-width: 280px;
            --header-height: 70px;
            --border-radius: 15px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        /* Header/Navbar */
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
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .user-menu {
            position: relative;
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
            transition: all 0.3s ease;
        }
        
        .user-dropdown:hover {
            background: rgba(255,255,255,0.2);
            color: white;
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            border-radius: var(--border-radius);
            margin-top: 0.5rem;
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
        
        .sidebar-header {
            padding: 2rem;
            text-align: center;
            border-bottom: 1px solid #eee;
        }
        
        .user-avatar {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            margin: 0 auto 1rem;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 1rem 0;
        }
        
        .sidebar-menu li {
            margin-bottom: 0.5rem;
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
        
        /* Overlay para móvil */
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
        
        /* Contenido principal */
        .main-content {
            margin-top: var(--header-height);
            padding: 2rem;
            min-height: calc(100vh - var(--header-height));
            transition: margin-left 0.3s ease;
        }
        
        /* Cards de bienvenida */
        .welcome-card {
            background: linear-gradient(135deg, white 0%, #f8f9fa 100%);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        /* Grid de módulos */
        .modules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
            gap: 2rem;
        }
        
        .module-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
        }
        
        .module-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--module-color);
        }
        
        .module-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .module-header {
            padding: 2rem 2rem 1rem;
            display: flex;
            align-items: flex-start;
            gap: 1.5rem;
        }
        
        .module-icon {
            width: 70px;
            height: 70px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.8rem;
            background: var(--module-color);
        }
        
        .module-info h3 {
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 700;
        }
        
        .module-description {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.4;
        }
        
        .module-stats {
            padding: 1rem 2rem;
            border-top: 1px solid #f0f0f0;
        }
        
        .stats-list {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        
        .stat-item {
            text-align: center;
            flex: 1;
        }
        
        .stat-value {
            font-weight: 600;
            color: #333;
            font-size: 0.9rem;
        }
        
        .stat-label {
            font-size: 0.75rem;
            color: #999;
            margin-top: 0.2rem;
        }
        
        .module-footer {
            padding: 1rem 2rem 2rem;
        }
        
        .btn-module {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid var(--module-color);
            background: white;
            color: var(--module-color);
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .btn-module:hover {
            background: var(--module-color);
            color: white;
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
            .modules-grid {
                grid-template-columns: 1fr;
            }
            
            .main-content {
                padding: 1rem;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        /* Animaciones */
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
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
                <a href="dashboard.php" class="logo-brand">
                    <i class="fas fa-building me-2"></i>
                    Sistema de Gestión
                </a>
            </div>
            
            <div class="user-menu">
                <div class="dropdown">
                    <button class="user-dropdown dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i>
                        <span class="d-none d-md-inline"><?php echo htmlspecialchars($user_name); ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <div class="dropdown-header">
                                <strong><?php echo htmlspecialchars($user_name); ?></strong><br>
                                <small class="text-muted"><?php echo htmlspecialchars($username); ?></small>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Mi Perfil</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Configuración</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-bell me-2"></i>Notificaciones</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <h6 class="mb-1"><?php echo htmlspecialchars($user_name); ?></h6>
            <small class="text-muted"><?php echo ucfirst($user_role); ?></small>
        </div>
        
        <ul class="sidebar-menu">
            <li><a href="dashboard.php" class="active"><i class="fas fa-home"></i>Dashboard</a></li>
            <?php foreach ($modules as $key => $module): ?>
            <li><a href="<?php echo $module['url']; ?>"><i class="<?php echo $module['icon']; ?>"></i><?php echo $module['name']; ?></a></li>
            <?php endforeach; ?>
            <li><hr class="my-2"></li>
            <li><a href="#"><i class="fas fa-chart-bar"></i>Reportes</a></li>
            <li><a href="#"><i class="fas fa-cog"></i>Configuración</a></li>
            <li><a href="#"><i class="fas fa-question-circle"></i>Ayuda</a></li>
        </ul>
    </nav>
    
    <!-- Overlay para móvil -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
    
    <!-- Contenido Principal -->
    <main class="main-content">
        <!-- Card de Bienvenida -->
        <div class="welcome-card fade-in">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="h3 mb-2">¡Bienvenido de vuelta, <?php echo htmlspecialchars(explode(' ', $user_name)[0]); ?>! 👋</h1>
                    <p class="text-muted mb-3">Aquí tienes un resumen de tu sistema de gestión empresarial</p>
                    <div class="d-flex gap-3">
                        <span class="badge bg-primary px-3 py-2">
                            <i class="fas fa-calendar me-1"></i>
                            <?php echo date('d/m/Y'); ?>
                        </span>
                        <span class="badge bg-success px-3 py-2">
                            <i class="fas fa-clock me-1"></i>
                            <?php echo date('H:i'); ?>
                        </span>
                    </div>
                </div>
                <div class="col-md-4 text-end d-none d-md-block">
                    <i class="fas fa-chart-line fa-4x text-primary opacity-25"></i>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-grid fade-in">
            <div class="stat-card">
                <div class="stat-number">6</div>
                <div class="text-muted">Módulos Activos</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">1,847</div>
                <div class="text-muted">Registros Totales</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">98.5%</div>
                <div class="text-muted">Uptime Sistema</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">15</div>
                <div class="text-muted">Usuarios Online</div>
            </div>
        </div>
        
        <!-- Grid de Módulos -->
        <div class="modules-grid">
            <?php foreach ($modules as $key => $module): ?>
            <div class="module-card fade-in" style="--module-color: <?php echo $module['color']; ?>">
                <div class="module-header">
                    <div class="module-icon">
                        <i class="<?php echo $module['icon']; ?>"></i>
                    </div>
                    <div class="module-info">
                        <h3><?php echo $module['name']; ?></h3>
                        <p class="module-description"><?php echo $module['description']; ?></p>
                    </div>
                </div>
                
                <div class="module-stats">
                    <div class="stats-list">
                        <?php foreach ($module['stats'] as $stat): ?>
                        <div class="stat-item">
                            <div class="stat-value"><?php echo explode(' ', $stat)[0]; ?></div>
                            <div class="stat-label"><?php echo substr($stat, strpos($stat, ' ') + 1); ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="module-footer">
                    <a href="<?php echo $module['url']; ?>" class="btn-module">
                        <i class="fas fa-arrow-right"></i>
                        Acceder al Módulo
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
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
        
        // Animaciones en scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animation = 'fadeIn 0.6s ease-out forwards';
                }
            });
        }, observerOptions);
        
        // Observar elementos
        document.querySelectorAll('.module-card').forEach(card => {
            observer.observe(card);
        });
        
        // Actualizar hora cada minuto
        setInterval(() => {
            const timeElement = document.querySelector('.badge:nth-child(2)');
            if (timeElement) {
                const now = new Date();
                const timeString = now.toLocaleTimeString('es-ES', { 
                    hour: '2-digit', 
                    minute: '2-digit' 
                });
                timeElement.innerHTML = `<i class="fas fa-clock me-1"></i>${timeString}`;
            }
        }, 60000);
        
        // Efecto hover en las cards
        document.querySelectorAll('.module-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    </script>
</body>
</html>
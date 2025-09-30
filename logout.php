<?php
session_start();

// Guardar información para mensaje de despedida
$user_name = $_SESSION['user_name'] ?? 'Usuario';

// Destruir todas las variables de sesión
$_SESSION = array();

// Si se desea destruir la sesión completamente, borre también la cookie de sesión
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruir la sesión
session_destroy();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesión Cerrada - Sistema de Gestión</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .logout-container {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 90%;
            animation: fadeIn 0.6s ease-out;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .logout-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            color: white;
            font-size: 2.5rem;
        }
        
        .btn-return {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            color: white;
            padding: 1rem 2rem;
            border-radius: 25px;
            font-weight: 600;
            transition: transform 0.3s ease;
        }
        
        .btn-return:hover {
            transform: translateY(-2px);
            color: white;
        }
    </style>
    <script>
        // Redirección automática después de 5 segundos
        let countdown = 5;
        
        function updateCountdown() {
            document.getElementById('countdown').textContent = countdown;
            countdown--;
            
            if (countdown < 0) {
                window.location.href = 'index.php';
            }
        }
        
        // Iniciar countdown
        setInterval(updateCountdown, 1000);
    </script>
</head>
<body>
    <div class="logout-container">
        <div class="logout-icon">
            <i class="fas fa-sign-out-alt"></i>
        </div>
        
        <h2 class="mb-3">¡Hasta luego, <?php echo htmlspecialchars($user_name); ?>!</h2>
        <p class="text-muted mb-4">Tu sesión ha sido cerrada correctamente.</p>
        
        <div class="alert alert-success" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            Sesión cerrada de forma segura
        </div>
        
        <p class="mb-4">
            Serás redirigido automáticamente en <span id="countdown" class="fw-bold text-primary">5</span> segundos.
        </p>
        
        <a href="index.php" class="btn btn-return">
            <i class="fas fa-sign-in-alt me-2"></i>
            Iniciar Sesión Nuevamente
        </a>
    </div>
</body>
</html>
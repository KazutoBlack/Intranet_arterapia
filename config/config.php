<?php
/**
 * Configuración General del Sistema
 * Sistema de Gestión Empresarial
 */

// Configuración de la aplicación
define('APP_NAME', 'Sistema de Gestión');
define('APP_VERSION', '1.0.0');
define('APP_DESCRIPTION', 'Plataforma integral para la administración empresarial');

// Configuración de URLs
define('BASE_URL', 'http://localhost/sistema-gestion/');
define('ASSETS_URL', BASE_URL . 'assets/');

// Configuración de rutas
define('ROOT_PATH', __DIR__ . '/../');
define('MODULES_PATH', ROOT_PATH . 'modules/');
define('INCLUDES_PATH', ROOT_PATH . 'includes/');
define('UPLOADS_PATH', ROOT_PATH . 'assets/uploads/');

// Configuración de sesión
define('SESSION_TIMEOUT', 8 * 60 * 60); // 8 horas en segundos
define('SESSION_NAME', 'sistema_gestion_session');

// Configuración de seguridad
define('HASH_ALGORITHM', PASSWORD_DEFAULT);
define('CSRF_TOKEN_LENGTH', 32);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_BLOCK_TIME', 15 * 60); // 15 minutos

// Configuración de archivos
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB
define('ALLOWED_FILE_TYPES', [
    'pdf', 'doc', 'docx', 'xls', 'xlsx', 
    'jpg', 'jpeg', 'png', 'gif',
    'txt', 'csv'
]);

// Configuración de módulos disponibles
$modules_config = [
    'recursos-humanos' => [
        'name' => 'Recursos Humanos',
        'icon' => 'fas fa-users',
        'color' => '#28a745',
        'enabled' => true,
        'roles' => ['admin', 'manager', 'hr']
    ],
    'fichas' => [
        'name' => 'Fichas',
        'icon' => 'fas fa-id-card',
        'color' => '#007bff',
        'enabled' => true,
        'roles' => ['admin', 'manager', 'medical']
    ],
    'documentos' => [
        'name' => 'Administración de Documentos',
        'icon' => 'fas fa-file-alt',
        'color' => '#fd7e14',
        'enabled' => true,
        'roles' => ['admin', 'manager', 'secretary']
    ],
    'giftcard' => [
        'name' => 'Gift Cards',
        'icon' => 'fas fa-gift',
        'color' => '#dc3545',
        'enabled' => true,
        'roles' => ['admin', 'manager', 'sales']
    ],
    'finanzas' => [
        'name' => 'Finanzas',
        'icon' => 'fas fa-chart-line',
        'color' => '#6f42c1',
        'enabled' => true,
        'roles' => ['admin', 'manager', 'accountant']
    ],
    'servicios' => [
        'name' => 'Servicios',
        'icon' => 'fas fa-cogs',
        'color' => '#6c757d',
        'enabled' => true,
        'roles' => ['admin', 'manager', 'service']
    ]
];

// Configuración de roles y permisos
$roles_config = [
    'admin' => [
        'name' => 'Administrador',
        'level' => 5,
        'permissions' => ['*'] // Todos los permisos
    ],
    'manager' => [
        'name' => 'Gerente',
        'level' => 4,
        'permissions' => ['read', 'write', 'update', 'reports']
    ],
    'hr' => [
        'name' => 'Recursos Humanos',
        'level' => 3,
        'permissions' => ['read', 'write', 'update']
    ],
    'accountant' => [
        'name' => 'Contador',
        'level' => 3,
        'permissions' => ['read', 'write', 'update']
    ],
    'employee' => [
        'name' => 'Empleado',
        'level' => 1,
        'permissions' => ['read']
    ]
];

// Configuración de base de datos (para desarrollo)
$db_config = [
    'host' => 'localhost',
    'dbname' => 'sistema_gestion',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]
];

// Configuración de correo (para notificaciones)
$mail_config = [
    'smtp_host' => 'smtp.gmail.com',
    'smtp_port' => 587,
    'smtp_username' => '',
    'smtp_password' => '',
    'from_email' => 'noreply@sistema.com',
    'from_name' => APP_NAME
];

// Configuración de la interfaz
$ui_config = [
    'theme' => 'default',
    'sidebar_collapsed' => false,
    'items_per_page' => 25,
    'date_format' => 'd/m/Y',
    'time_format' => 'H:i',
    'currency' => 'USD',
    'currency_symbol' => '$'
];

// Funciones de utilidad
function getModulesConfig() {
    global $modules_config;
    return $modules_config;
}

function getRolesConfig() {
    global $roles_config;
    return $roles_config;
}

function getDBConfig() {
    global $db_config;
    return $db_config;
}

function getUIConfig() {
    global $ui_config;
    return $ui_config;
}

function hasModuleAccess($module_key, $user_role) {
    global $modules_config;
    
    if (!isset($modules_config[$module_key])) {
        return false;
    }
    
    $module = $modules_config[$module_key];
    
    if (!$module['enabled']) {
        return false;
    }
    
    if ($user_role === 'admin') {
        return true;
    }
    
    return in_array($user_role, $module['roles']);
}

function formatCurrency($amount) {
    global $ui_config;
    return $ui_config['currency_symbol'] . number_format($amount, 2);
}

function formatDate($date, $format = null) {
    global $ui_config;
    $format = $format ?: $ui_config['date_format'];
    return date($format, strtotime($date));
}

function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(CSRF_TOKEN_LENGTH));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && 
           hash_equals($_SESSION['csrf_token'], $token);
}

function sanitizeInput($input) {
    if (is_array($input)) {
        return array_map('sanitizeInput', $input);
    }
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

function redirectTo($url, $message = null, $type = 'info') {
    if ($message) {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }
    header("Location: $url");
    exit();
}

function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        $type = $_SESSION['flash_type'] ?? 'info';
        
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
        
        return ['message' => $message, 'type' => $type];
    }
    return null;
}

// Autoload de clases (si usas clases personalizadas)
spl_autoload_register(function ($class_name) {
    $class_file = INCLUDES_PATH . 'classes/' . $class_name . '.php';
    if (file_exists($class_file)) {
        require_once $class_file;
    }
});

// Inicializar configuración de zona horaria
date_default_timezone_set('America/Mexico_City');

// Configuración de manejo de errores
error_reporting(E_ALL);
ini_set('display_errors', 0); // En producción cambiar a 0
ini_set('log_errors', 1);
ini_set('error_log', ROOT_PATH . 'logs/error.log');

// Función para logging personalizado
function writeLog($message, $level = 'INFO') {
    $log_file = ROOT_PATH . 'logs/app.log';
    $log_dir = dirname($log_file);
    
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[$timestamp] [$level] $message" . PHP_EOL;
    
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
}

// Configuración completada
define('CONFIG_LOADED', true);
?>

<!-- 
INSTRUCCIONES DE INSTALACIÓN:

1. Crear la estructura de carpetas según el esquema proporcionado
2. Colocar este archivo en config/config.php
3. Ajustar las configuraciones según tu entorno
4. Crear la base de datos 'sistema_gestion'
5. Configurar permisos de escritura en assets/uploads/ y logs/
6. Acceder a index.php para comenzar

CREDENCIALES POR DEFECTO:
Usuario: admin
Contraseña: admin123

Para cambiar las credenciales, modifica el archivo index.php
o implementa la validación con base de datos.
-->
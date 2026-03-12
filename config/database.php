<?php
/**
 * SwiftTrace - Database Connection
 * Utilizando PDO para máxima seguridad contra Inyección SQL.
 */

// Configuración del entorno (Cambia esto cuando subas a InfinityFree o tu hosting)
$host = 'localhost';
$db   = 'swift_trace';
$user = 'tu_usuario_bd';
$pass = 'tu_contraseña_bd';
$charset = 'utf8mb4';

// Data Source Name
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Opciones de seguridad y rendimiento de PDO
$options = [
    // Lanza excepciones si hay un error (útil para el try/catch)
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    // Devuelve los resultados como un array asociativo limpio
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    // Desactiva la emulación para usar consultas preparadas reales (más seguro)
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Instancia de conexión
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // En un entorno de producción, NUNCA mostramos $e->getMessage() al usuario
    // porque revelaría la contraseña o rutas del servidor.
    error_log("Error de conexión a la BD: " . $e->getMessage());
    die("Error crítico: Servicio temporalmente no disponible.");
}

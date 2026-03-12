<?php
/**
 * SwiftTrace - Database Connection
 * Configuración optimizada para InfinityFree
 */

$host = 'sql207.infinityfree.com';
$db   = 'if0_41375704_swifttrace'; // Nombre de la base de datos que creaste
$user = 'if0_41375704';           // Tu MySQL Username de la imagen
$pass = 'FD3wbZ6LMe43';           // Tu MySQL Password de la imagen
$charset = 'utf8mb4';

// Data Source Name
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Opciones de seguridad y rendimiento de PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Instancia de conexión
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Registra el error en el servidor y muestra un mensaje genérico
    error_log("Error de conexión a la BD: " . $e->getMessage());
    die("Error crítico: Servicio de base de datos no disponible actualmente.");
}

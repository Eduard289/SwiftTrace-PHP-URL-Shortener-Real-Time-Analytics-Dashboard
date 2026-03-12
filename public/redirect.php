<?php
/**
 * SwiftTrace - Core Redirect Engine
 * Procesa el clic, registra la analítica y redirige.
 */

// 1. Cargar dependencias (conexión segura y motor analítico)
require_once '../config/database.php';
require_once '../core/Tracker.php';

// 2. Capturar el código de la URL (ej: tuweb.com/abc -> captura 'abc')
// Filtramos la entrada para evitar caracteres extraños básicos
$code = filter_input(INPUT_GET, 'code', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if (empty($code)) {
    // Si alguien entra directamente a redirect.php sin código, lo mandamos al inicio
    header("Location: index.php");
    exit;
}

try {
    // 3. Buscar la URL original en la base de datos (Consulta Preparada)
    $stmt = $pdo->prepare("SELECT id, long_url FROM links WHERE short_code = ? LIMIT 1");
    $stmt->execute([$code]);
    $link = $stmt->fetch();

    if ($link) {
        // 4. ¡El enlace existe! Capturamos los datos del visitante
        $visitorData = Tracker::getUserData();
        
        // 5. Guardamos la analítica en la base de datos de forma asíncrona/transparente
        $logStmt = $pdo->prepare("
            INSERT INTO analytics (link_id, ip_address, user_agent, os, browser, referer) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $logStmt->execute([
            $link['id'],
            $visitorData['ip'],
            $visitorData['user_agent'],
            $visitorData['os'],
            $visitorData['browser'],
            $visitorData['referer']
        ]);

        // 6. Ejecutamos la redirección permanente (SEO-friendly)
        header("Location: " . $link['long_url'], true, 301);
        exit;
    } else {
        // 7. Si el código no está en la base de datos
        http_response_code(404);
        echo "<h1>404 - Enlace no encontrado</h1>";
        echo "<p>El enlace que buscas no existe o ha sido eliminado.</p>";
    }

} catch (PDOException $e) {
    // Si falla la base de datos, no rompemos la pantalla del usuario
    error_log("Error crítico en redirección: " . $e->getMessage());
    http_response_code(500);
    die("Servicio temporalmente no disponible.");
}

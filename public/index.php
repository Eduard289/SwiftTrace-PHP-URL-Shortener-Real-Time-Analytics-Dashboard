<?php
require_once '../config/database.php';

$message = '';
$shortenedUrl = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $longUrl = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
    
    if ($longUrl) {
        // 1. Generar un código corto aleatorio único (6 caracteres)
        $shortCode = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
        
        try {
            // 2. Insertar en la base de datos
            $stmt = $pdo->prepare("INSERT INTO links (short_code, long_url) VALUES (?, ?)");
            $stmt->execute([$shortCode, $longUrl]);
            
            // 3. Construir la URL corta para mostrarla
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
            $domain = $_SERVER['HTTP_HOST'];
            $shortenedUrl = "$protocol://$domain/$shortCode";
            $message = "¡Enlace acortado con éxito!";
            
        } catch (PDOException $e) {
            $message = "Error: El código ya existe, intenta de nuevo.";
        }
    } else {
        $message = "Por favor, introduce una URL válida.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwiftTrace | URL Shortener</title>
    <style>
        :root { --primary: #6366f1; --bg: #f8fafc; --text: #1e293b; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; background: var(--bg); color: var(--text); display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .container { background: white; padding: 2rem; border-radius: 1rem; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); width: 100%; max-width: 450px; text-align: center; }
        h1 { margin-bottom: 0.5rem; color: var(--primary); }
        p { color: #64748b; margin-bottom: 1.5rem; }
        input[type="url"] { width: 100%; padding: 0.8rem; border: 2px solid #e2e8f0; border-radius: 0.5rem; box-sizing: border-box; margin-bottom: 1rem; font-size: 1rem; }
        button { background: var(--primary); color: white; border: none; padding: 0.8rem 1.5rem; border-radius: 0.5rem; font-weight: 600; cursor: pointer; width: 100%; transition: opacity 0.2s; }
        button:hover { opacity: 0.9; }
        .result { margin-top: 1.5rem; padding: 1rem; background: #eef2ff; border-radius: 0.5rem; border: 1px dashed var(--primary); }
        .result a { color: var(--primary); font-weight: bold; text-decoration: none; word-break: break-all; }
        .msg { font-size: 0.9rem; color: #ef4444; margin-top: 1rem; }
        .stats-link { margin-top: 2rem; display: block; font-size: 0.8rem; color: #94a3b8; text-decoration: none; }
    </style>
</head>
<body>

<div class="container">
    <h1>SwiftTrace</h1>
    <p>Introduce una URL larga y nosotros la hacemos diminuta.</p>
    
    <form method="POST">
        <input type="url" name="url" placeholder="https://ejemplo.com/mi-url-larga" required>
        <button type="submit">Acortar Enlace</button>
    </form>

    <?php if ($shortenedUrl): ?>
        <div class="result">
            <p>Tu enlace corto es:</p>
            <a href="<?php echo $shortenedUrl; ?>" target="_blank"><?php echo $shortenedUrl; ?></a>
        </div>
    <?php endif; ?>

    <?php if ($message && !$shortenedUrl): ?>
        <p class="msg"><?php echo $message; ?></p>
    <?php endif; ?>

    <a href="stats.php" class="stats-link">Ver Panel de Analíticas →</a>
</div>

</body>
</html>

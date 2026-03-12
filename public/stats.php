<?php
require_once '../config/database.php';

// 1. Obtener estadísticas generales por navegador
$browserStmt = $pdo->query("SELECT browser, COUNT(*) as count FROM analytics GROUP BY browser");
$browserData = $browserStmt->fetchAll();

// 2. Obtener estadísticas por Sistema Operativo
$osStmt = $pdo->query("SELECT os, COUNT(*) as count FROM analytics GROUP BY os");
$osData = $osStmt->fetchAll();

// 3. Obtener los 5 enlaces más clickeados
$topLinksStmt = $pdo->query("
    SELECT l.short_code, COUNT(a.id) as clicks 
    FROM links l 
    LEFT JOIN analytics a ON l.id = a.link_id 
    GROUP BY l.id 
    ORDER BY clicks DESC 
    LIMIT 5
");
$topLinks = $topLinksStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SwiftTrace | Insights Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background: #f1f5f9; margin: 0; padding: 40px; }
        .dashboard-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; max-width: 1200px; margin: auto; }
        .card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        h1 { text-align: center; color: #1e293b; margin-bottom: 40px; }
        h2 { font-size: 1.1rem; color: #475569; margin-bottom: 20px; }
        .back-btn { display: inline-block; margin-bottom: 20px; color: #6366f1; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>

    <h1>SwiftTrace Insights</h1>
    <div style="max-width: 1200px; margin: auto;">
        <a href="index.php" class="back-btn">← Volver al generador</a>
    </div>

    <div class="dashboard-grid">
        <div class="card">
            <h2>Navegadores Utilizados</h2>
            <canvas id="browserChart"></canvas>
        </div>

        <div class="card">
            <h2>Sistemas Operativos</h2>
            <canvas id="osChart"></canvas>
        </div>

        <div class="card">
            <h2>Top 5 Enlaces con más Clicks</h2>
            <ul style="list-style: none; padding: 0;">
                <?php foreach($topLinks as $link): ?>
                    <li style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between;">
                        <strong>/<?php echo $link['short_code']; ?></strong>
                        <span style="background: #eef2ff; color: #6366f1; padding: 2px 10px; border-radius: 20px; font-size: 0.8rem;">
                            <?php echo $link['clicks']; ?> clicks
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <script>
        // Configuración de Gráfico de Navegadores
        const browserCtx = document.getElementById('browserChart').getContext('2d');
        new Chart(browserCtx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode(array_column($browserData, 'browser')); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_column($browserData, 'count')); ?>,
                    backgroundColor: ['#6366f1', '#f43f5e', '#10b981', '#f59e0b', '#8b5cf6']
                }]
            }
        });

        // Configuración de Gráfico de SO
        const osCtx = document.getElementById('osChart').getContext('2d');
        new Chart(osCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($osData, 'os')); ?>,
                datasets: [{
                    label: 'Clicks',
                    data: <?php echo json_encode(array_column($osData, 'count')); ?>,
                    backgroundColor: '#6366f1'
                }]
            },
            options: { scales: { y: { beginAtZero: true } } }
        });
    </script>
</body>
</html>

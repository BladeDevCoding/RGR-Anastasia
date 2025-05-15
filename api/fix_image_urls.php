<?php
require_once 'db_functions.php';

// Функция для исправления опечаток в URL-адресах изображений десертов
function fixDesertImageUrls() {
    // Получаем все десерты
    $deserty = getAllDeserty();
    $fixed = 0;
    
    echo "<h2>Исправление опечаток в URL-адресах изображений десертов</h2>";
    
    foreach ($deserty as $desert) {
        // Проверяем наличие URL в базе данных
        if (!empty($desert['foto_url'])) {
            $foto_url = $desert['foto_url'];
            $original_url = $foto_url;
            
            // Исправление опечаток в именах файлов
            if (strpos($foto_url, 'new_work_cheese cake.jpg') !== false) {
                $foto_url = str_replace('new_work_cheese cake.jpg', 'new_york_cheesecake.jpg', $foto_url);
            }
            
            if (strpos($foto_url, 'chocolate_fonda n.jpg') !== false) {
                $foto_url = str_replace('chocolate_fonda n.jpg', 'chocolate_fondan.jpg', $foto_url);
            }
            
            // Обновляем URL в базе данных, если были исправления
            if ($foto_url !== $original_url) {
                $conn = connectDB();
                $stmt = $conn->prepare("UPDATE deserty SET foto_url = ? WHERE id = ?");
                $stmt->bind_param("si", $foto_url, $desert['id']);
                $result = $stmt->execute();
                
                if ($result) {
                    echo "<p>Десерт <strong>" . htmlspecialchars($desert['nazva']) . "</strong>: URL исправлен с <br>
                    <code>" . htmlspecialchars($original_url) . "</code> на <br>
                    <code>" . htmlspecialchars($foto_url) . "</code></p>";
                    $fixed++;
                } else {
                    echo "<p>Ошибка при обновлении URL для десерта " . htmlspecialchars($desert['nazva']) . "</p>";
                }
                
                $stmt->close();
                $conn->close();
            }
        }
    }
    
    if ($fixed === 0) {
        echo "<p>Опечаток в URL-адресах не найдено.</p>";
    } else {
        echo "<p>Исправлено URL-адресов: $fixed</p>";
    }
}

?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Исправление URL-адресов изображений</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        h1, h2 {
            color: #5d4037;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .btn {
            display: inline-block;
            padding: 8px 12px;
            background-color: #795548;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 5px;
            margin-bottom: 10px;
        }
        .success {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        code {
            display: block;
            background-color: #f5f5f5;
            padding: 5px;
            margin: 5px 0;
            border-radius: 3px;
            font-family: monospace;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Исправление URL-адресов изображений</h1>
        
        <p>
            <a href="admin.php" class="btn">← Вернуться в админ-панель</a>
        </p>
        
        <?php
        // Проверяем, был ли отправлен запрос на исправление
        if (isset($_GET['fix']) && $_GET['fix'] === 'true') {
            // Исправляем URL-адреса
            fixDesertImageUrls();
            
            echo "<div class='success'>Исправление URL-адресов завершено!</div>";
            echo "<p><a href='update_image_display.php' class='btn'>Перейти к обновлению отображения изображений</a></p>";
        } else {
        ?>
            <p>Нажмите кнопку ниже, чтобы исправить опечатки в URL-адресах изображений десертов:</p>
            <ul>
                <li>Исправление <code>new_work_cheese cake.jpg</code> на <code>new_york_cheesecake.jpg</code></li>
                <li>Исправление <code>chocolate_fonda n.jpg</code> на <code>chocolate_fondan.jpg</code></li>
            </ul>
            <p><a href="?fix=true" class="btn">Исправить URL-адреса</a></p>
        <?php
        }
        ?>
    </div>
</body>
</html>
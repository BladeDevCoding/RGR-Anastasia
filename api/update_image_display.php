<?php
require_once 'db_functions.php';

// Функция для обновления отображения изображений десертов
function updateDesertImageDisplay() {
    // Получаем все десерты
    $deserty = getAllDeserty();
    
    echo "<h2>Обновление отображения изображений десертов</h2>";
    
    foreach ($deserty as $desert) {
        echo "Десерт: " . htmlspecialchars($desert['nazva']) . " - ";
        
        // Проверяем наличие URL в базе данных
        if (!empty($desert['foto_url'])) {
            echo "URL найден: " . htmlspecialchars($desert['foto_url']) . "<br>";
            
            // Исправляем опечатки в URL
            $foto_url = $desert['foto_url'];
            
            // Исправление опечаток в именах файлов
            if (strpos($foto_url, 'new_work_cheese cake.jpg') !== false) {
                $foto_url = str_replace('new_work_cheese cake.jpg', 'new_york_cheesecake.jpg', $foto_url);
                echo "Исправлена опечатка в URL: " . htmlspecialchars($foto_url) . "<br>";
            }
            
            if (strpos($foto_url, 'chocolate_fonda n.jpg') !== false) {
                $foto_url = str_replace('chocolate_fonda n.jpg', 'chocolate_fondan.jpg', $foto_url);
                echo "Исправлена опечатка в URL: " . htmlspecialchars($foto_url) . "<br>";
            }
            
            // Обновляем URL в базе данных, если были исправления
            if ($foto_url !== $desert['foto_url']) {
                $conn = connectDB();
                $stmt = $conn->prepare("UPDATE deserty SET foto_url = ? WHERE id = ?");
                $stmt->bind_param("si", $foto_url, $desert['id']);
                $result = $stmt->execute();
                
                if ($result) {
                    echo "URL успешно обновлен<br>";
                } else {
                    echo "Ошибка при обновлении URL<br>";
                }
                
                $stmt->close();
                $conn->close();
            }
        } else {
            echo "URL не найден<br>";
        }
    }
}

// Функция для обновления отображения изображений кофеен
function updateKavyarnyaImageDisplay() {
    // Получаем все кофейни
    $kavarni = getAllKavyarni();
    
    echo "<h2>Обновление отображения изображений кофеен</h2>";
    
    foreach ($kavarni as $kavyarnya) {
        echo "Кофейня: " . htmlspecialchars($kavyarnya['nazva']) . " - ";
        
        // Проверяем наличие URL в базе данных
        if (!empty($kavyarnya['foto_url'])) {
            echo "URL найден: " . htmlspecialchars($kavyarnya['foto_url']) . "<br>";
        } else {
            echo "URL не найден<br>";
        }
    }
}

// Функция для обновления отображения изображений котиков
function updateKotykImageDisplay() {
    // Получаем всех котиков
    $kotyky = getAllKotyky();
    
    echo "<h2>Обновление отображения изображений котиков</h2>";
    
    foreach ($kotyky as $kotyk) {
        echo "Котик: " . htmlspecialchars($kotyk['imya']) . " - ";
        
        // Проверяем наличие URL в базе данных
        if (!empty($kotyk['foto_url'])) {
            echo "URL найден: " . htmlspecialchars($kotyk['foto_url']) . "<br>";
        } else {
            echo "URL не найден<br>";
        }
    }
}

// Создаем функции для обновления файлов отображения
function updateViewDesertFile() {
    $file_path = 'view_desert.php';
    $file_content = file_get_contents($file_path);
    
    // Заменяем строку с отображением изображения
    $old_img_tag = '<img src="images/deserts/<?php echo $desert_id; ?>.jpg" alt="<?php echo htmlspecialchars($desert[\'nazva\']); ?>" class="desert-image" onerror="this.src=\'https://via.placeholder.com/400x300?text=Десерт\'">';
    $new_img_tag = '<?php if (!empty($desert["foto_url"])): ?>
                <img src="<?php echo htmlspecialchars($desert["foto_url"]); ?>" alt="<?php echo htmlspecialchars($desert["nazva"]); ?>" class="desert-image">
            <?php else: ?>
                <img src="images/deserts/<?php echo $desert_id; ?>.jpg" alt="<?php echo htmlspecialchars($desert["nazva"]); ?>" class="desert-image" onerror="this.src=\'https://via.placeholder.com/400x300?text=Десерт\'">
            <?php endif; ?>';
    
    $updated_content = str_replace($old_img_tag, $new_img_tag, $file_content);
    
    if ($updated_content !== $file_content) {
        file_put_contents($file_path, $updated_content);
        echo "<p>Файл view_desert.php успешно обновлен</p>";
    } else {
        echo "<p>Не удалось обновить файл view_desert.php</p>";
    }
}

function updateViewKavyarnyaFile() {
    $file_path = 'view_kavyarnya.php';
    $file_content = file_get_contents($file_path);
    
    // Заменяем строку с отображением изображения
    $old_img_tag = '<?php if (file_exists(\'images/kavarni/\' . $kavyarnya_id . \'.jpg\')): ?>
                <img src="images/kavarni/<?php echo $kavyarnya_id; ?>.jpg" alt="<?php echo htmlspecialchars($kavyarnya[\'nazva\']); ?>" class="kavyarnya-image">
            <?php endif; ?>';
    
    $new_img_tag = '<?php if (!empty($kavyarnya["foto_url"])): ?>
                <img src="<?php echo htmlspecialchars($kavyarnya["foto_url"]); ?>" alt="<?php echo htmlspecialchars($kavyarnya["nazva"]); ?>" class="kavyarnya-image">
            <?php elseif (file_exists(\'images/kavarni/\' . $kavyarnya_id . \'.jpg\')): ?>
                <img src="images/kavarni/<?php echo $kavyarnya_id; ?>.jpg" alt="<?php echo htmlspecialchars($kavyarnya["nazva"]); ?>" class="kavyarnya-image">
            <?php endif; ?>';
    
    $updated_content = str_replace($old_img_tag, $new_img_tag, $file_content);
    
    if ($updated_content !== $file_content) {
        file_put_contents($file_path, $updated_content);
        echo "<p>Файл view_kavyarnya.php успешно обновлен</p>";
    } else {
        echo "<p>Не удалось обновить файл view_kavyarnya.php</p>";
    }
}

function updateViewKotykFile() {
    $file_path = 'view_kotyk.php';
    $file_content = file_get_contents($file_path);
    
    // Заменяем строку с отображением изображения
    $old_img_tag = '<img src="images/cats/<?php echo $kotyk_id; ?>.jpg" alt="<?php echo htmlspecialchars($kotyk[\'imya\']); ?>" class="kotyk-image" onerror="this.src=\'https://via.placeholder.com/400x300?text=Котик\'">';
    
    $new_img_tag = '<?php if (!empty($kotyk["foto_url"])): ?>
                <img src="<?php echo htmlspecialchars($kotyk["foto_url"]); ?>" alt="<?php echo htmlspecialchars($kotyk["imya"]); ?>" class="kotyk-image">
            <?php else: ?>
                <img src="images/cats/<?php echo $kotyk_id; ?>.jpg" alt="<?php echo htmlspecialchars($kotyk["imya"]); ?>" class="kotyk-image" onerror="this.src=\'https://via.placeholder.com/400x300?text=Котик\'">
            <?php endif; ?>';
    
    $updated_content = str_replace($old_img_tag, $new_img_tag, $file_content);
    
    if ($updated_content !== $file_content) {
        file_put_contents($file_path, $updated_content);
        echo "<p>Файл view_kotyk.php успешно обновлен</p>";
    } else {
        echo "<p>Не удалось обновить файл view_kotyk.php</p>";
    }
}

// Создаем HTML-страницу
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Обновление отображения изображений</title>
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
        .error {
            background-color: #f44336;
            color: white;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Обновление отображения изображений</h1>
        
        <p>
            <a href="admin.php" class="btn">← Вернуться в админ-панель</a>
        </p>
        
        <?php
        // Проверяем, был ли отправлен запрос на обновление
        if (isset($_GET['update']) && $_GET['update'] === 'true') {
            // Обновляем отображение изображений
            updateDesertImageDisplay();
            updateKavyarnyaImageDisplay();
            updateKotykImageDisplay();
            
            // Обновляем файлы отображения
            updateViewDesertFile();
            updateViewKavyarnyaFile();
            updateViewKotykFile();
            
            echo "<div class='success'>Обновление завершено!</div>";
        } else {
        ?>
            <p>Нажмите кнопку ниже, чтобы обновить отображение изображений для десертов, кофеен и котиков.</p>
            <p>Это действие:</p>
            <ul>
                <li>Исправит опечатки в URL-адресах изображений десертов</li>
                <li>Обновит файлы отображения для использования URL-адресов из базы данных</li>
                <li>Сохранит обратную совместимость с локальными файлами</li>
            </ul>
            <p><a href="?update=true" class="btn">Обновить отображение изображений</a></p>
        <?php
        }
        ?>
    </div>
</body>
</html>
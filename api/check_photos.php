<?php
require_once 'db_functions.php';

// Функция для проверки существования файла
function checkFileExists($path) {
    $fullPath = __DIR__ . '/' . $path;
    $exists = file_exists($fullPath);
    return [
        'path' => $path,
        'fullPath' => $fullPath,
        'exists' => $exists
    ];
}

// Получаем все десерты
$deserty = getAllDeserty();
$desertPhotos = [];
foreach ($deserty as $desert) {
    // Проверяем путь из базы данных
    $dbPath = $desert['foto_url'];
    $dbPathResult = $dbPath ? checkFileExists($dbPath) : ['path' => 'Не указан', 'exists' => false];
    
    // Проверяем путь, используемый в view_desert.php
    $viewPath = 'images/deserts/' . $desert['id'] . '.jpg';
    $viewPathResult = checkFileExists($viewPath);
    
    $desertPhotos[] = [
        'id' => $desert['id'],
        'nazva' => $desert['nazva'],
        'dbPath' => $dbPathResult,
        'viewPath' => $viewPathResult
    ];
}

// Получаем все кофейни
$kavarni = getAllKavyarni();
$kavarniPhotos = [];
foreach ($kavarni as $kavyarnya) {
    // Проверяем путь из базы данных
    $dbPath = $kavyarnya['foto_url'];
    $dbPathResult = $dbPath ? checkFileExists($dbPath) : ['path' => 'Не указан', 'exists' => false];
    
    // Проверяем путь, используемый в view_kavyarnya.php
    $viewPath = 'images/kavarni/' . $kavyarnya['id'] . '.jpg';
    $viewPathResult = checkFileExists($viewPath);
    
    $kavarniPhotos[] = [
        'id' => $kavyarnya['id'],
        'nazva' => $kavyarnya['nazva'],
        'dbPath' => $dbPathResult,
        'viewPath' => $viewPathResult
    ];
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Проверка фотографий - KityKoffe</title>
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
            max-width: 1200px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
        .photo-preview {
            max-width: 100px;
            max-height: 100px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Проверка фотографий</h1>
        
        <h2>Десерты</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Путь в БД</th>
                    <th>Существует?</th>
                    <th>Путь в view_desert.php</th>
                    <th>Существует?</th>
                    <th>Предпросмотр</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($desertPhotos as $photo): ?>
                <tr>
                    <td><?php echo $photo['id']; ?></td>
                    <td><?php echo htmlspecialchars($photo['nazva']); ?></td>
                    <td><?php echo htmlspecialchars($photo['dbPath']['path']); ?></td>
                    <td class="<?php echo $photo['dbPath']['exists'] ? 'success' : 'error'; ?>">
                        <?php echo $photo['dbPath']['exists'] ? 'Да' : 'Нет'; ?>
                    </td>
                    <td><?php echo htmlspecialchars($photo['viewPath']['path']); ?></td>
                    <td class="<?php echo $photo['viewPath']['exists'] ? 'success' : 'error'; ?>">
                        <?php echo $photo['viewPath']['exists'] ? 'Да' : 'Нет'; ?>
                    </td>
                    <td>
                        <?php if ($photo['dbPath']['exists']): ?>
                            <img src="<?php echo htmlspecialchars($photo['dbPath']['path']); ?>" alt="<?php echo htmlspecialchars($photo['nazva']); ?>" class="photo-preview">
                        <?php elseif ($photo['viewPath']['exists']): ?>
                            <img src="<?php echo htmlspecialchars($photo['viewPath']['path']); ?>" alt="<?php echo htmlspecialchars($photo['nazva']); ?>" class="photo-preview">
                        <?php else: ?>
                            Нет фото
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <h2>Кофейни</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Путь в БД</th>
                    <th>Существует?</th>
                    <th>Путь в view_kavyarnya.php</th>
                    <th>Существует?</th>
                    <th>Предпросмотр</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($kavarniPhotos as $photo): ?>
                <tr>
                    <td><?php echo $photo['id']; ?></td>
                    <td><?php echo htmlspecialchars($photo['nazva']); ?></td>
                    <td><?php echo htmlspecialchars($photo['dbPath']['path']); ?></td>
                    <td class="<?php echo $photo['dbPath']['exists'] ? 'success' : 'error'; ?>">
                        <?php echo $photo['dbPath']['exists'] ? 'Да' : 'Нет'; ?>
                    </td>
                    <td><?php echo htmlspecialchars($photo['viewPath']['path']); ?></td>
                    <td class="<?php echo $photo['viewPath']['exists'] ? 'success' : 'error'; ?>">
                        <?php echo $photo['viewPath']['exists'] ? 'Да' : 'Нет'; ?>
                    </td>
                    <td>
                        <?php if ($photo['dbPath']['exists']): ?>
                            <img src="<?php echo htmlspecialchars($photo['dbPath']['path']); ?>" alt="<?php echo htmlspecialchars($photo['nazva']); ?>" class="photo-preview">
                        <?php elseif ($photo['viewPath']['exists']): ?>
                            <img src="<?php echo htmlspecialchars($photo['viewPath']['path']); ?>" alt="<?php echo htmlspecialchars($photo['nazva']); ?>" class="photo-preview">
                        <?php else: ?>
                            Нет фото
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
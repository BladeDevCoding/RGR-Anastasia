<?php
require_once 'db_functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$desert_id = intval($_GET['id']);
$desert = getDesertById($desert_id);

if (!$desert) {
    header('Location: index.php');
    exit;
}

$success = isset($_GET['success']) ? true : false;
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($desert['nazva']); ?> - Деталі</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        h1, h2, h3 {
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
        }
        .btn-edit {
            background-color: #2196F3;
        }
        .btn-delete {
            background-color: #f44336;
        }
        .btn-secondary {
            background-color: #9E9E9E;
        }
        .success {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .info-block {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            width: 200px;
        }
        .info-value {
            flex: 1;
        }
        .desert-image {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($desert['nazva']); ?></h1>
        
        <p>
            <a href="admin.php?section=deserty" class="btn btn-secondary">← Повернутися до списку</a>
            <a href="edit_desert.php?id=<?php echo $desert_id; ?>" class="btn btn-edit">Редагувати</a>
            <a href="delete_desert.php?id=<?php echo $desert_id; ?>" class="btn btn-delete" onclick="return confirm('Ви впевнені, що хочете видалити цей десерт?');">Видалити</a>
        </p>
        
        <?php if ($success): ?>
            <div class="success">Операція успішно виконана!</div>
        <?php endif; ?>
        
        <div class="info-block">
            <h2>Фото десерту</h2>
            <?php if (!empty($desert["foto_url"])): ?>
                <img src="<?php echo htmlspecialchars($desert["foto_url"]); ?>" alt="<?php echo htmlspecialchars($desert["nazva"]); ?>" class="desert-image">
            <?php else: ?>
                <img src="images/deserts/<?php echo $desert_id; ?>.jpg" alt="<?php echo htmlspecialchars($desert["nazva"]); ?>" class="desert-image" onerror="this.src='https://via.placeholder.com/400x300?text=Десерт'">
            <?php endif; ?>
        </div>
        
        <div class="info-block">
            <h2>Основна інформація</h2>
            
            <div class="info-row">
                <div class="info-label">Назва:</div>
                <div class="info-value"><?php echo htmlspecialchars($desert['nazva']); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Опис:</div>
                <div class="info-value"><?php echo nl2br(htmlspecialchars($desert['opis'])); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Ціна:</div>
                <div class="info-value"><?php echo number_format($desert['tsina'], 2); ?> грн</div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Вага:</div>
                <div class="info-value"><?php echo $desert['vaha_gram']; ?> г</div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Категорія:</div>
                <div class="info-value"><?php echo htmlspecialchars($desert['kategoria'] ?? 'Не вказано'); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Доступність:</div>
                <div class="info-value"><?php echo $desert['dostupnyy'] ? 'Так' : 'Ні'; ?></div>
            </div>
        </div>
    </div>
</body>
</html>
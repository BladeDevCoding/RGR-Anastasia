<?php
require_once 'db_functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$kotyk_id = intval($_GET['id']);
$kotyk = getKotykById($kotyk_id);

if (!$kotyk) {
    header('Location: index.php');
    exit;
}

$kavyarnya = getKavyarnyaById($kotyk['kav_yarnya_id']);
$success = isset($_GET['success']) ? true : false;
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($kotyk['imya']); ?> - Деталі</title>
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
        .kotyk-image {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($kotyk['imya']); ?></h1>
        
        <p>
            <a href="admin.php?section=kotyky" class="btn btn-secondary">← Повернутися до списку</a>
            <a href="edit_kotyk.php?id=<?php echo $kotyk_id; ?>" class="btn btn-edit">Редагувати</a>
            <a href="delete_kotyk.php?id=<?php echo $kotyk_id; ?>" class="btn btn-delete" onclick="return confirm('Ви впевнені, що хочете видалити цього котика?');">Видалити</a>
        </p>
        
        <?php if ($success): ?>
            <div class="success">Операція успішно виконана!</div>
        <?php endif; ?>
        
        <div class="info-block">
            <h2>Фото котика</h2>
            <?php if (!empty($kotyk["foto_url"])): ?>
                <img src="<?php echo htmlspecialchars($kotyk["foto_url"]); ?>" alt="<?php echo htmlspecialchars($kotyk["imya"]); ?>" class="kotyk-image">
            <?php else: ?>
                <img src="images/cats/<?php echo $kotyk_id; ?>.jpg" alt="<?php echo htmlspecialchars($kotyk["imya"]); ?>" class="kotyk-image" onerror="this.src='https://via.placeholder.com/400x300?text=Котик'">
            <?php endif; ?>
        </div>
        
        <div class="info-block">
            <h2>Основна інформація</h2>
            
            <div class="info-row">
                <div class="info-label">Ім'я:</div>
                <div class="info-value"><?php echo htmlspecialchars($kotyk['imya']); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Вік:</div>
                <div class="info-value"><?php echo $kotyk['vik']; ?> років</div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Стать:</div>
                <div class="info-value"><?php echo htmlspecialchars($kotyk['stat']); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Порода:</div>
                <div class="info-value"><?php echo htmlspecialchars($kotyk['poroda']); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Характеристика:</div>
                <div class="info-value"><?php echo nl2br(htmlspecialchars($kotyk['harakterystyka'])); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Кав'ярня:</div>
                <div class="info-value">
                    <?php if ($kavyarnya): ?>
                        <a href="kavarni.php?id=<?php echo $kavyarnya['id']; ?>">
                            <?php echo htmlspecialchars($kavyarnya['nazva']); ?>
                        </a>
                    <?php else: ?>
                        Не вказано
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
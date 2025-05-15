<?php
require_once 'db_functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$aktsiya_id = intval($_GET['id']);
$aktsiya = getAktsiyaById($aktsiya_id);

if (!$aktsiya) {
    header('Location: index.php');
    exit;
}

$kavyarnya = null;
if ($aktsiya['kav_yarnya_id']) {
    $kavyarnya = getKavyarnyaById($aktsiya['kav_yarnya_id']);
}

$success = isset($_GET['success']) ? true : false;
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($aktsiya['nazva']); ?> - Деталі</title>
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
        .discount-badge {
            display: inline-block;
            background-color: #e53935;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($aktsiya['nazva']); ?></h1>
        
        <p>
            <a href="admin.php?section=aktsiyi" class="btn btn-secondary">← Повернутися до списку</a>
            <a href="edit_aktsiya.php?id=<?php echo $aktsiya_id; ?>" class="btn btn-edit">Редагувати</a>
            <a href="delete_aktsiya.php?id=<?php echo $aktsiya_id; ?>" class="btn btn-delete" onclick="return confirm('Ви впевнені, що хочете видалити цю акцію?');">Видалити</a>
        </p>
        
        <?php if ($success): ?>
            <div class="success">Операція успішно виконана!</div>
        <?php endif; ?>
        
        <div class="info-block">
            <h2>Основна інформація</h2>
            
            <div class="discount-badge">Знижка: <?php echo $aktsiya['znyzhka']; ?>%</div>
            
            <div class="info-row">
                <div class="info-label">Назва:</div>
                <div class="info-value"><?php echo htmlspecialchars($aktsiya['nazva']); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Опис:</div>
                <div class="info-value"><?php echo nl2br(htmlspecialchars($aktsiya['opis'])); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Дата початку:</div>
                <div class="info-value"><?php echo date('d.m.Y', strtotime($aktsiya['data_pochatku'])); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Дата закінчення:</div>
                <div class="info-value"><?php echo date('d.m.Y', strtotime($aktsiya['data_zakinchennya'])); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Кав'ярня:</div>
                <div class="info-value">
                    <?php if ($kavyarnya): ?>
                        <a href="kavarni.php?id=<?php echo $kavyarnya['id']; ?>">
                            <?php echo htmlspecialchars($kavyarnya['nazva']); ?>
                        </a>
                    <?php else: ?>
                        Всі кав'ярні
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Статус:</div>
                <div class="info-value">
                    <?php 
                    $current_date = date('Y-m-d');
                    if ($aktsiya['data_pochatku'] > $current_date): ?>
                        <span style="color: #FFA000;">Очікується</span>
                    <?php elseif ($aktsiya['data_zakinchennya'] < $current_date): ?>
                        <span style="color: #F44336;">Завершена</span>
                    <?php else: ?>
                        <span style="color: #4CAF50;">Активна</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
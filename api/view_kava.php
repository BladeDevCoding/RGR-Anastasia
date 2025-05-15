<?php
require_once 'db_functions.php';


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$kava_id = intval($_GET['id']);
$kava = getKavaById($kava_id);


if (!$kava) {
    header('Location: index.php');
    exit;
}


$ingredienty = getIngredientsForKava($kava_id);


$retsepty = getRetseptyByKavaId($kava_id);


$success = isset($_GET['success']) ? true : false;
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($kava['nazva']); ?> - Деталі</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .recipe-step {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 4px;
        }
        .step-number {
            font-weight: bold;
            margin-right: 10px;
            color: #5d4037;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($kava['nazva']); ?></h1>
        
        <p>
            <a href="index.php" class="btn btn-secondary">← Повернутися до списку</a>
            <a href="edit_kava.php?id=<?php echo $kava_id; ?>" class="btn btn-edit">Редагувати</a>
            <a href="delete_kava.php?id=<?php echo $kava_id; ?>" class="btn btn-delete" onclick="return confirm('Ви впевнені, що хочете видалити цей вид кави?');">Видалити</a>
        </p>
        
        <?php if ($success): ?>
            <div class="success">Операція успішно виконана!</div>
        <?php endif; ?>
        
        <div class="info-block">
            <h2>Основна інформація</h2>
            
            <div class="info-row">
                <div class="info-label">Назва:</div>
                <div class="info-value"><?php echo htmlspecialchars($kava['nazva']); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Опис:</div>
                <div class="info-value"><?php echo nl2br(htmlspecialchars($kava['opis'])); ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Ціна:</div>
                <div class="info-value"><?php echo number_format($kava['tsina'], 2); ?> грн</div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Час приготування:</div>
                <div class="info-value"><?php echo $kava['chas_prihotuvannya']; ?> сек</div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Доступність:</div>
                <div class="info-value"><?php echo $kava['dostupna'] ? 'Так' : 'Ні'; ?></div>
            </div>
        </div>
        
        <div class="info-block">
            <h2>Інгредієнти</h2>
            
            <?php if (count($ingredienty) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Назва</th>
                            <th>Кількість</th>
                            <th>Одиниця виміру</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ingredienty as $ingredient): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($ingredient['nazva']); ?></td>
                                <td><?php echo $ingredient['kilkist']; ?></td>
                                <td><?php echo htmlspecialchars($ingredient['odynytsya']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Для цієї кави не вказано інгредієнтів</p>
            <?php endif; ?>
        </div>
        
        <div class="info-block">
            <h2>Рецепт приготування</h2>
            
            <?php if (count($retsepty) > 0): ?>
                <div class="recipe-steps">
                    <?php foreach ($retsepty as $retsept): ?>
                        <div class="recipe-step">
                            <span class="step-number">Крок <?php echo $retsept['krok']; ?>:</span>
                            <?php echo htmlspecialchars($retsept['instruktsiya']); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Для цієї кави не вказано рецепту приготування</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
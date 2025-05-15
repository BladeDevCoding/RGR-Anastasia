<?php
require_once 'db_functions.php';

$message = '';
$error = '';


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


$ingredienty = getAllIngredienty();

$kava_ingredienty = getIngredientsForKava($kava_id);
$kava_ingredienty_map = [];

foreach ($kava_ingredienty as $ki) {
    $kava_ingredienty_map[$ki['ingredient_id']] = $ki['kilkist'];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    $nazva = trim($_POST['nazva']);
    $opis = trim($_POST['opis']);
    $tsina = floatval($_POST['tsina']);
    $chas_prihotuvannya = intval($_POST['chas_prihotuvannya']);
    $dostupna = isset($_POST['dostupna']) ? 1 : 0;
    
 
    if (empty($nazva)) {
        $error = 'Назва кави обов\'язкова';
    } elseif ($tsina <= 0) {
        $error = 'Ціна повинна бути більше нуля';
    } elseif ($chas_prihotuvannya <= 0) {
        $error = 'Час приготування повинен бути більше нуля';
    } else {
       
        $result = updateKava($kava_id, $nazva, $opis, $tsina, $chas_prihotuvannya, $dostupna);
        
        if ($result) {
           
            if (isset($_POST['ingredients']) && is_array($_POST['ingredients'])) {
                foreach ($_POST['ingredients'] as $ingredient_id => $kilkist) {
                    $kilkist = floatval($kilkist);
                    if ($kilkist > 0) {
                        addIngredientToKava($kava_id, $ingredient_id, $kilkist);
                    } else {
                       
                        removeIngredientFromKava($kava_id, $ingredient_id);
                    }
                }
            }
            
            $message = 'Інформацію про каву успішно оновлено!';
       
            $kava = getKavaById($kava_id);
            $kava_ingredienty = getIngredientsForKava($kava_id);
            $kava_ingredienty_map = [];
            
            foreach ($kava_ingredienty as $ki) {
                $kava_ingredienty_map[$ki['ingredient_id']] = $ki['kilkist'];
            }
        } else {
            $error = 'Помилка при оновленні інформації про каву';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редагувати каву</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        h1 {
            color: #5d4037;
            text-align: center;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            height: 100px;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #795548;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }
        .btn-primary {
            background-color: #2196F3;
        }
        .btn-secondary {
            background-color: #9E9E9E;
        }
        .error {
            color: #f44336;
            margin-bottom: 15px;
        }
        .success {
            color: #4CAF50;
            margin-bottom: 15px;
        }
        .ingredients-container {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .ingredient-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .ingredient-item label {
            margin-right: 10px;
            width: 200px;
        }
        .ingredient-item input {
            width: 100px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Редагувати каву</h1>
        
        <p>
            <a href="admin.php?section=kava" class="btn btn-secondary">← Повернутися до списку</a>
            <a href="view_kava.php?id=<?php echo $kava_id; ?>" class="btn">Переглянути деталі</a>
        </p>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($message): ?>
            <div class="success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <form method="post" action="">
            <div class="form-group">
                <label for="nazva">Назва кави *</label>
                <input type="text" id="nazva" name="nazva" required value="<?php echo htmlspecialchars($kava['nazva']); ?>">
            </div>
            
            <div class="form-group">
                <label for="opis">Опис</label>
                <textarea id="opis" name="opis"><?php echo htmlspecialchars($kava['opis']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="tsina">Ціна (грн) *</label>
                <input type="number" id="tsina" name="tsina" step="0.01" min="0" required value="<?php echo $kava['tsina']; ?>">
            </div>
            
            <div class="form-group">
                <label for="chas_prihotuvannya">Час приготування (сек) *</label>
                <input type="number" id="chas_prihotuvannya" name="chas_prihotuvannya" min="1" required value="<?php echo $kava['chas_prihotuvannya']; ?>">
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="dostupna" value="1" <?php echo $kava['dostupna'] ? 'checked' : ''; ?>>
                    Доступна для продажу
                </label>
            </div>
            
            <div class="form-group">
                <h3>Інгредієнти</h3>
                <div class="ingredients-container">
                    <?php if (count($ingredienty) > 0): ?>
                        <?php foreach ($ingredienty as $ingredient): ?>
                            <div class="ingredient-item">
                                <label>
                                    <?php echo htmlspecialchars($ingredient['nazva']); ?> (<?php echo htmlspecialchars($ingredient['odynytsya']); ?>)
                                </label>
                                <input type="number" name="ingredients[<?php echo $ingredient['id']; ?>]" min="0" step="0.01" 
                                       value="<?php echo isset($kava_ingredienty_map[$ingredient['id']]) ? $kava_ingredienty_map[$ingredient['id']] : 0; ?>">
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Немає доступних інгредієнтів</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Зберегти зміни</button>
            </div>
        </form>
    </div>
</body>
</html>
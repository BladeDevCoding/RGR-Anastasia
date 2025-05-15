<?php
require_once 'db_functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admin.php?section=ingredients');
    exit;
}

$ingredient_id = intval($_GET['id']);
$ingredient = getIngredientById($ingredient_id);

if (!$ingredient) {
    header('Location: admin.php?section=ingredients');
    exit;
}

$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nazva = trim($_POST['nazva'] ?? '');
    $odynytsya = trim($_POST['odynytsya'] ?? '');
    
    if (empty($nazva)) {
        $error = 'Назва інгредієнта обов\'язкова';
    } elseif (empty($odynytsya)) {
        $error = 'Одиниця виміру обов\'язкова';
    } else {
        $result = updateIngredient($ingredient_id, $nazva, $odynytsya);
        
        if ($result) {
            header('Location: admin.php?section=ingredients&success=1');
            exit;
        } else {
            $error = 'Помилка при оновленні інгредієнта';
        }
    }
} else {
    // Заполняем форму текущими данными
    $_POST = $ingredient;
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редагувати інгредієнт - KityKoffe</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .form-container {
            max-width: 600px;
            margin: 20px auto;
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
        input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
        .btn-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="form-container">
        <h1>Редагувати інгредієнт</h1>
        
        <p>
            <a href="admin.php?section=ingredients" class="btn btn-secondary">← Повернутися до списку</a>
        </p>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="post">
            <div class="form-group">
                <label for="nazva">Назва інгредієнта *</label>
                <input type="text" id="nazva" name="nazva" required value="<?php echo isset($_POST['nazva']) ? htmlspecialchars($_POST['nazva']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="odynytsya">Одиниця виміру *</label>
                <input type="text" id="odynytsya" name="odynytsya" required value="<?php echo isset($_POST['odynytsya']) ? htmlspecialchars($_POST['odynytsya']) : ''; ?>">
            </div>
            
            <div class="btn-container">
                <button type="submit" class="btn">Зберегти зміни</button>
            </div>
        </form>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>
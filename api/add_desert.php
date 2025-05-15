<?php
require_once 'db_functions.php';

$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nazva = trim($_POST['nazva'] ?? '');
    $opis = trim($_POST['opis'] ?? '');
    $tsina = floatval($_POST['tsina'] ?? 0);
    $vaha_gram = intval($_POST['vaha_gram'] ?? 0);
    $dostupnyy = isset($_POST['dostupnyy']) ? 1 : 0;
    $kategoria = trim($_POST['kategoria'] ?? '');
    
    if (empty($nazva)) {
        $error = 'Назва десерту обов\'язкова';
    } elseif ($tsina <= 0) {
        $error = 'Ціна повинна бути більше нуля';
    } elseif ($vaha_gram <= 0) {
        $error = 'Вага повинна бути більше нуля';
    } else {
        $desert_id = addDesert($nazva, $opis, $tsina, $vaha_gram, $dostupnyy, $kategoria);
        
        if ($desert_id) {
            // Обработка загрузки изображения
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
                $upload_dir = 'images/deserts/';
                
                // Создаем директорию, если она не существует
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $file_extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                $target_file = $upload_dir . $desert_id . '.' . $file_extension;
                
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
                    // Файл успешно загружен
                } else {
                    $error = 'Помилка при завантаженні фото';
                }
            }
            
            header('Location: admin.php?section=deserty&success=1');
            exit;
        } else {
            $error = 'Помилка при додаванні десерту';
        }
    }
}

$kavarni = getAllKavyarni();
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Додати новий десерт - KityKoffe</title>
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
        .checkbox-group {
            margin-top: 5px;
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
        <h1>Додати новий десерт</h1>
        
        <p>
            <a href="admin.php?section=deserty" class="btn">← Повернутися до списку</a>
        </p>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nazva">Назва десерту *</label>
                <input type="text" id="nazva" name="nazva" required value="<?php echo isset($_POST['nazva']) ? htmlspecialchars($_POST['nazva']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="opis">Опис</label>
                <textarea id="opis" name="opis"><?php echo isset($_POST['opis']) ? htmlspecialchars($_POST['opis']) : ''; ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="tsina">Ціна (грн) *</label>
                <input type="number" id="tsina" name="tsina" step="0.01" min="0" required value="<?php echo isset($_POST['tsina']) ? htmlspecialchars($_POST['tsina']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="vaha_gram">Вага (г) *</label>
                <input type="number" id="vaha_gram" name="vaha_gram" min="1" required value="<?php echo isset($_POST['vaha_gram']) ? htmlspecialchars($_POST['vaha_gram']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="kategoria">Категорія</label>
                <input type="text" id="kategoria" name="kategoria" value="<?php echo isset($_POST['kategoria']) ? htmlspecialchars($_POST['kategoria']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="photo">Фото десерту</label>
                <input type="file" id="photo" name="photo" accept="image/*">
            </div>
            
            <div class="form-group checkbox-group">
                <input type="checkbox" id="dostupnyy" name="dostupnyy" <?php echo (!isset($_POST['dostupnyy']) || $_POST['dostupnyy']) ? 'checked' : ''; ?>>
                <label for="dostupnyy" style="display: inline;">Доступний для замовлення</label>
            </div>
            
            <div class="btn-container">
                <button type="submit" class="btn">Додати десерт</button>
            </div>
        </form>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>
<?php
require_once 'db_functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admin.php?section=kavarni');
    exit;
}

$kavyarnya_id = intval($_GET['id']);
$kavyarnya = getKavyarnyaById($kavyarnya_id);

if (!$kavyarnya) {
    header('Location: admin.php?section=kavarni');
    exit;
}

$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nazva = trim($_POST['nazva'] ?? '');
    $adresa = trim($_POST['adresa'] ?? '');
    $telefon = trim($_POST['telefon'] ?? '');
    $grafik_roboty = trim($_POST['grafik_roboty'] ?? '');
    $opys = trim($_POST['opys'] ?? '');
    
    if (empty($nazva)) {
        $error = 'Назва кав\'ярні обов\'язкова';
    } elseif (empty($adresa)) {
        $error = 'Адреса кав\'ярні обов\'язкова';
    } else {
        $result = updateKavyarnya($kavyarnya_id, $nazva, $adresa, $telefon, $grafik_roboty, $opys);
        
        if ($result) {
            // Обработка загрузки изображения
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
                $upload_dir = 'images/kavarni/';
                
                // Создаем директорию, если она не существует
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $file_extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                $target_file = $upload_dir . $kavyarnya_id . '.' . $file_extension;
                
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
                    // Файл успешно загружен
                }
            }
            
            header('Location: admin.php?section=kavarni&success=1');
            exit;
        } else {
            $error = 'Помилка при оновленні кав\'ярні';
        }
    }
} else {
    // Заполняем форму текущими данными
    $_POST = $kavyarnya;
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редагувати кав'ярню - KityKoffe</title>
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
        input[type="tel"],
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            height: 100px;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
        .btn-container {
            margin-top: 20px;
        }
        .current-image {
            max-width: 200px;
            margin-top: 10px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="form-container">
        <h1>Редагувати кав'ярню</h1>
        
        <p>
            <a href="admin.php?section=kavarni" class="btn btn-secondary">← Повернутися до списку</a>
            <a href="view_kavyarnya.php?id=<?php echo $kavyarnya_id; ?>" class="btn">Переглянути деталі</a>
        </p>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nazva">Назва кав'ярні *</label>
                <input type="text" id="nazva" name="nazva" required value="<?php echo isset($_POST['nazva']) ? htmlspecialchars($_POST['nazva']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="adresa">Адреса *</label>
                <input type="text" id="adresa" name="adresa" required value="<?php echo isset($_POST['adresa']) ? htmlspecialchars($_POST['adresa']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="telefon">Телефон</label>
                <input type="tel" id="telefon" name="telefon" value="<?php echo isset($_POST['telefon']) ? htmlspecialchars($_POST['telefon']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="grafik_roboty">Графік роботи</label>
                <input type="text" id="grafik_roboty" name="grafik_roboty" value="<?php echo isset($_POST['grafik_roboty']) ? htmlspecialchars($_POST['grafik_roboty']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="opys">Опис кав'ярні</label>
                <textarea id="opys" name="opys"><?php echo isset($_POST['opys']) ? htmlspecialchars($_POST['opys']) : ''; ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="photo">Фото кав'ярні</label>
                <input type="file" id="photo" name="photo" accept="image/*">
                <?php 
                // Проверяем наличие фото
                $photo_extensions = ['jpg', 'jpeg', 'png', 'gif'];
                $photo_exists = false;
                foreach ($photo_extensions as $ext) {
                    if (file_exists('images/kavarni/' . $kavyarnya_id . '.' . $ext)) {
                        $photo_exists = true;
                        $photo_ext = $ext;
                        break;
                    }
                }
                if ($photo_exists): 
                ?>
                <div>
                    <p>Поточне фото:</p>
                    <img src="images/kavarni/<?php echo $kavyarnya_id . '.' . $photo_ext; ?>" alt="<?php echo htmlspecialchars($kavyarnya['nazva']); ?>" class="current-image">
                </div>
                <?php endif; ?>
            </div>
            
            <div class="btn-container">
                <button type="submit" class="btn">Зберегти зміни</button>
            </div>
        </form>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>
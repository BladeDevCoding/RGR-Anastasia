<?php
require_once 'db_functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admin.php?section=kotyky');
    exit;
}

$kotyk_id = intval($_GET['id']);
$kotyk = getKotykById($kotyk_id);

if (!$kotyk) {
    header('Location: admin.php?section=kotyky');
    exit;
}

$error = '';
$success = false;

// Получаем список кофеен для выбора
$kavarni = getAllKavyarni();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imya = trim($_POST['imya'] ?? '');
    $vik = intval($_POST['vik'] ?? 0);
    $stat = trim($_POST['stat'] ?? '');
    $poroda = trim($_POST['poroda'] ?? '');
    $harakterystyka = trim($_POST['harakterystyka'] ?? '');
    $kav_yarnya_id = intval($_POST['kav_yarnya_id'] ?? 0);
    
    if (empty($imya)) {
        $error = 'Ім\'я котика обов\'язкове';
    } elseif ($vik <= 0) {
        $error = 'Вік повинен бути більше нуля';
    } elseif (empty($stat)) {
        $error = 'Стать котика обов\'язкова';
    } elseif ($kav_yarnya_id <= 0) {
        $error = 'Виберіть кав\'ярню';
    } else {
        $result = updateKotyk($kotyk_id, $imya, $vik, $stat, $poroda, $harakterystyka, $kav_yarnya_id);
        
        if ($result) {
            // Обработка загрузки изображения
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
                $upload_dir = 'images/cats/';
                
                // Создаем директорию, если она не существует
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $file_extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                $target_file = $upload_dir . $kotyk_id . '.' . $file_extension;
                
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
                    // Файл успешно загружен
                } else {
                    $error = 'Помилка при завантаженні фото';
                }
            }
            
            header('Location: view_kotyk.php?id=' . $kotyk_id . '&success=1');
            exit;
        } else {
            $error = 'Помилка при оновленні котика';
        }
    }
} else {
    // Заполняем форму текущими данными
    $_POST = $kotyk;
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редагувати котика - KityKoffe</title>
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
        <h1>Редагувати котика</h1>
        
        <p>
            <a href="admin.php?section=kotyky" class="btn btn-secondary">← Повернутися до списку</a>
            <a href="view_kotyk.php?id=<?php echo $kotyk_id; ?>" class="btn">Переглянути деталі</a>
        </p>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="imya">Ім'я котика *</label>
                <input type="text" id="imya" name="imya" required value="<?php echo isset($_POST['imya']) ? htmlspecialchars($_POST['imya']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="vik">Вік (років) *</label>
                <input type="number" id="vik" name="vik" min="1" required value="<?php echo isset($_POST['vik']) ? htmlspecialchars($_POST['vik']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="stat">Стать *</label>
                <select id="stat" name="stat" required>
                    <option value="">Виберіть стать</option>
                    <option value="Чоловіча" <?php echo (isset($_POST['stat']) && $_POST['stat'] == 'Чоловіча') ? 'selected' : ($kotyk['stat'] == 'Чоловіча' ? 'selected' : ''); ?>>Чоловіча</option>
                    <option value="Жіноча" <?php echo (isset($_POST['stat']) && $_POST['stat'] == 'Жіноча') ? 'selected' : ($kotyk['stat'] == 'Жіноча' ? 'selected' : ''); ?>>Жіноча</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="poroda">Порода</label>
                <input type="text" id="poroda" name="poroda" value="<?php echo isset($_POST['poroda']) ? htmlspecialchars($_POST['poroda']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="harakterystyka">Характеристика</label>
                <textarea id="harakterystyka" name="harakterystyka"><?php echo isset($_POST['harakterystyka']) ? htmlspecialchars($_POST['harakterystyka']) : ''; ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="kav_yarnya_id">Кав'ярня *</label>
                <select id="kav_yarnya_id" name="kav_yarnya_id" required>
                    <option value="">Виберіть кав'ярню</option>
                    <?php foreach ($kavarni as $kavyarnya): ?>
                        <option value="<?php echo $kavyarnya['id']; ?>" <?php echo (isset($_POST['kav_yarnya_id']) && $_POST['kav_yarnya_id'] == $kavyarnya['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($kavyarnya['nazva']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="photo">Фото котика</label>
                <input type="file" id="photo" name="photo" accept="image/*">
                <?php if (file_exists('images/cats/' . $kotyk_id . '.jpg')): ?>
                    <div>
                        <p>Поточне фото:</p>
                        <img src="images/cats/<?php echo $kotyk_id; ?>.jpg" alt="<?php echo htmlspecialchars($kotyk['imya']); ?>" class="current-image">
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
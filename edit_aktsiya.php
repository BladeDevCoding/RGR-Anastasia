<?php
require_once 'db_functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admin.php?section=aktsiyi');
    exit;
}

$aktsiya_id = intval($_GET['id']);
$aktsiya = getAktsiyaById($aktsiya_id);

if (!$aktsiya) {
    header('Location: admin.php?section=aktsiyi');
    exit;
}

$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nazva = trim($_POST['nazva'] ?? '');
    $opis = trim($_POST['opis'] ?? '');
    $znyzhka = floatval($_POST['znyzhka'] ?? 0);
    $data_pochatku = $_POST['data_pochatku'] ?? '';
    $data_zakinchennya = $_POST['data_zakinchennya'] ?? '';
    $kav_yarnya_id = !empty($_POST['kav_yarnya_id']) ? intval($_POST['kav_yarnya_id']) : null;
    
    if (empty($nazva)) {
        $error = 'Назва акції обов\'язкова';
    } elseif ($znyzhka <= 0 || $znyzhka > 100) {
        $error = 'Знижка повинна бути більше нуля і не більше 100%';
    } elseif (empty($data_pochatku)) {
        $error = 'Дата початку обов\'язкова';
    } elseif (empty($data_zakinchennya)) {
        $error = 'Дата закінчення обов\'язкова';
    } elseif (strtotime($data_zakinchennya) < strtotime($data_pochatku)) {
        $error = 'Дата закінчення не може бути раніше дати початку';
    } else {
        $result = updateAktsiya($aktsiya_id, $nazva, $opis, $znyzhka, $data_pochatku, $data_zakinchennya, $kav_yarnya_id);
        
        if ($result) {
            header('Location: view_aktsiya.php?id=' . $aktsiya_id . '&success=1');
            exit;
        } else {
            $error = 'Помилка при оновленні акції';
        }
    }
} else {
    // Заполняем форму текущими данными
    $_POST = $aktsiya;
}

// Получаем список кав'ярень для выпадающего списка
$kavyarni = getAllKavyarni();
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редагувати акцію - KityKoffe</title>
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
        input[type="date"],
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
        <h1>Редагувати акцію</h1>
        
        <p>
            <a href="view_aktsiya.php?id=<?php echo $aktsiya_id; ?>" class="btn">← Повернутися до деталей</a>
        </p>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="post">
            <div class="form-group">
                <label for="nazva">Назва акції *</label>
                <input type="text" id="nazva" name="nazva" required value="<?php echo isset($_POST['nazva']) ? htmlspecialchars($_POST['nazva']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="opis">Опис</label>
                <textarea id="opis" name="opis"><?php echo isset($_POST['opis']) ? htmlspecialchars($_POST['opis']) : ''; ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="znyzhka">Знижка (%) *</label>
                <input type="number" id="znyzhka" name="znyzhka" step="0.1" min="0" max="100" required value="<?php echo isset($_POST['znyzhka']) ? htmlspecialchars($_POST['znyzhka']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="data_pochatku">Дата початку *</label>
                <input type="date" id="data_pochatku" name="data_pochatku" required value="<?php echo isset($_POST['data_pochatku']) ? htmlspecialchars($_POST['data_pochatku']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="data_zakinchennya">Дата закінчення *</label>
                <input type="date" id="data_zakinchennya" name="data_zakinchennya" required value="<?php echo isset($_POST['data_zakinchennya']) ? htmlspecialchars($_POST['data_zakinchennya']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="kav_yarnya_id">Кав'ярня (залиште порожнім для всіх кав'ярень)</label>
                <select id="kav_yarnya_id" name="kav_yarnya_id">
                    <option value="">Всі кав'ярні</option>
                    <?php foreach ($kavyarni as $kavyarnya): ?>
                        <option value="<?php echo $kavyarnya['id']; ?>" <?php echo (isset($_POST['kav_yarnya_id']) && $_POST['kav_yarnya_id'] == $kavyarnya['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($kavyarnya['nazva']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="btn-container">
                <button type="submit" class="btn">Зберегти зміни</button>
            </div>
        </form>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>
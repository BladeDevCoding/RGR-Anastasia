<?php
require_once 'db_functions.php';

$error = '';
$success = false;

// Получаем список кофеен для выбора
$kavarni = getAllKavyarni();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nazva = trim($_POST['nazva'] ?? '');
    $opis = trim($_POST['opis'] ?? '');
    $znyzhka = floatval($_POST['znyzhka'] ?? 0);
    $data_pochatku = trim($_POST['data_pochatku'] ?? '');
    $data_zakinchennya = trim($_POST['data_zakinchennya'] ?? '');
    $kav_yarnya_id = !empty($_POST['kav_yarnya_id']) ? intval($_POST['kav_yarnya_id']) : null;
    
    if (empty($nazva)) {
        $error = 'Назва акції обов\'язкова';
    } elseif ($znyzhka <= 0 || $znyzhka > 100) {
        $error = 'Знижка повинна бути від 1 до 100 відсотків';
    } elseif (empty($data_pochatku)) {
        $error = 'Дата початку обов\'язкова';
    } elseif (empty($data_zakinchennya)) {
        $error = 'Дата закінчення обов\'язкова';
    } elseif (strtotime($data_zakinchennya) < strtotime($data_pochatku)) {
        $error = 'Дата закінчення не може бути раніше дати початку';
    } else {
        $aktsiya_id = addAktsiya($nazva, $opis, $znyzhka, $data_pochatku, $data_zakinchennya, $kav_yarnya_id);
        
        if ($aktsiya_id) {
            header('Location: admin.php?section=aktsiyi&success=1');
            exit;
        } else {
            $error = 'Помилка при додаванні акції';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Додати нову акцію - KityKoffe</title>
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
        <h1>Додати нову акцію</h1>
        
        <p>
            <a href="admin.php?section=aktsiyi" class="btn">← Повернутися до списку</a>
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
                <label for="opis">Опис акції</label>
                <textarea id="opis" name="opis"><?php echo isset($_POST['opis']) ? htmlspecialchars($_POST['opis']) : ''; ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="znyzhka">Знижка (%) *</label>
                <input type="number" id="znyzhka" name="znyzhka" min="1" max="100" required value="<?php echo isset($_POST['znyzhka']) ? htmlspecialchars($_POST['znyzhka']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="data_pochatku">Дата початку *</label>
                <input type="date" id="data_pochatku" name="data_pochatku" required value="<?php echo isset($_POST['data_pochatku']) ? htmlspecialchars($_POST['data_pochatku']) : date('Y-m-d'); ?>">
            </div>
            
            <div class="form-group">
                <label for="data_zakinchennya">Дата закінчення *</label>
                <input type="date" id="data_zakinchennya" name="data_zakinchennya" required value="<?php echo isset($_POST['data_zakinchennya']) ? htmlspecialchars($_POST['data_zakinchennya']) : date('Y-m-d', strtotime('+30 days')); ?>">
            </div>
            
            <div class="form-group">
                <label for="kav_yarnya_id">Кав'ярня (необов'язково)</label>
                <select id="kav_yarnya_id" name="kav_yarnya_id">
                    <option value="">Всі кав'ярні</option>
                    <?php foreach ($kavarni as $kavyarnya): ?>
                        <option value="<?php echo $kavyarnya['id']; ?>" <?php echo (isset($_POST['kav_yarnya_id']) && $_POST['kav_yarnya_id'] == $kavyarnya['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($kavyarnya['nazva']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="btn-container">
                <button type="submit" class="btn">Додати акцію</button>
            </div>
        </form>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>
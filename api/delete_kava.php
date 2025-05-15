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


if (isset($_GET['confirm']) && $_GET['confirm'] == 1) {
    // Видаляємо каву та пов'язані записи
    $result = deleteKava($kava_id);
    
    if ($result) {
        header('Location: index.php?deleted=1');
    } else {
      
        header("Location: view_kava.php?id=$kava_id&error=1");
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Видалення кави</title>
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
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .warning {
            background-color: #ffebee;
            border-left: 4px solid #f44336;
            padding: 15px;
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 10px;
            color: white;
        }
        .btn-delete {
            background-color: #f44336;
        }
        .btn-cancel {
            background-color: #9E9E9E;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Видалення кави</h1>
        
        <div class="warning">
            <p><strong>Увага!</strong> Ви збираєтесь видалити каву "<?php echo htmlspecialchars($kava['nazva']); ?>".</p>
            <p>Ця дія також видалить всі пов'язані рецепти та інгредієнти для цієї кави.</p>
            <p>Ця дія є незворотною. Ви впевнені, що хочете продовжити?</p>
        </div>
        
        <div>
            <a href="delete_kava.php?id=<?php echo $kava_id; ?>&confirm=1" class="btn btn-delete">Так, видалити</a>
            <a href="view_kava.php?id=<?php echo $kava_id; ?>" class="btn btn-cancel">Скасувати</a>
        </div>
    </div>
</body>
</html>
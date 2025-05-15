<?php
session_start();
require_once 'connection.php';

$error = '';

// Если пользователь уже авторизован, перенаправляем на админ-панель
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: admin.php');
    exit;
}

// Обработка формы входа
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = isset($_POST['login']) ? trim($_POST['login']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    
    // Проверка учетных данных
    if ($login === 'Anastasias_Coffe' && $password === 'AskaChan274992') {
        // Успешная авторизация
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $login;
        
        // Перенаправление на админ-панель
        header('Location: admin.php');
        exit;
    } else {
        $error = 'Невірний логін або пароль';
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вхід в адмін-панель - KityKoffe</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .error-message {
            color: #f44336;
            margin-bottom: 15px;
        }
        .login-btn {
            background-color: #795548;
            width: 100%;
            padding: 12px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="login-container">
        <h2>Вхід в адміністративну панель</h2>
        
        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="post" action="">
            <div class="form-group">
                <label for="login">Логін:</label>
                <input type="text" id="login" name="login" required>
            </div>
            
            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn login-btn">Увійти</button>
        </form>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>
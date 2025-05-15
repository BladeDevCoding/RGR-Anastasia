<?php
// Налаштування сесії для роботи з Vercel
ini_set('session.cookie_secure', '0');
ini_set('session.cookie_httponly', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.cookie_samesite', 'None');
ini_set('session.cookie_lifetime', '86400');
session_start();
require_once 'connection.php';

// Для автентифікації в Vercel використовуємо параметри GET, щоб уникнути проблем з сесіями
$auth_token = 'AskaChan274992'; // Токен для автентифікації

// Якщо передано токен, автоматично авторизуємо користувача
if (isset($_GET['token']) && $_GET['token'] === $auth_token) {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_username'] = 'Anastasias_Coffe';
    $_SESSION['auth_time'] = time();
    
    header('Location: admin.php');
    exit;
}

$error = '';

// Перевіряємо параметр expired
if (isset($_GET['expired']) && $_GET['expired'] == '1') {
    $error = 'Ваша сесія закінчилася. Будь ласка, увійдіть знову.';
}

// Перевіряємо параметр logout
if (isset($_GET['logout']) && $_GET['logout'] == '1') {
    $error = 'Ви успішно вийшли з адмін-панелі.';
    // Очищаємо куки
    if (isset($_SERVER['HTTP_COOKIE'])) {
        $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
        foreach($cookies as $cookie) {
            $parts = explode('=', $cookie);
            $name = trim($parts[0]);
            setcookie($name, '', time()-1000);
            setcookie($name, '', time()-1000, '/');
        }
    }
}

// Если пользователь уже авторизован, перенаправляем на админ-панель
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    // Перевіряємо, чи вже не перенаправлялися на цю сторінку з admin.php
    if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'admin.php') !== false) {
        // Якщо були перенаправлені з admin.php, то сесія некоректна - очищаємо її
        $_SESSION = array();
        session_destroy();
        session_start();
        $error = 'Помилка авторизації. Будь ласка, увійдіть знову.';
    } else {
        header('Location: admin.php');
        exit;
    }
}

// Якщо запит на перенаправлення, показуємо спеціальний шаблон з автоматичним перенаправленням
$redirect_to_admin = isset($_GET['redirect']) && $_GET['redirect'] === 'admin';

// Обработка формы входа
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = isset($_POST['login']) ? trim($_POST['login']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    
    // Проверка учетных данных
    if ($login === 'Anastasias_Coffe' && $password === 'AskaChan274992') {
        // Очищаємо стару сесію перш ніж створити нову
        $_SESSION = array();
        session_regenerate_id(true);
        
        // Успешная авторизация
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $login;
        $_SESSION['auth_time'] = time();
        
        // Перенаправление на админ-панель з токеном, щоб гарантувати авторизацію
        header('Location: admin.php?token=' . $auth_token);
        exit;
    } else {
        $error = 'Невірний логін або пароль';
    }
}

// Якщо був запит на перенаправлення в адмін-панель
if ($redirect_to_admin) {
    // Видаємо сторінку з автоматичним перенаправленням
    ?>
    <!DOCTYPE html>
    <html lang="uk">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Перенаправлення до адмін-панелі</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                text-align: center;
                padding: 50px;
            }
            .loader {
                border: 5px solid #f3f3f3;
                border-top: 5px solid #795548;
                border-radius: 50%;
                width: 50px;
                height: 50px;
                animation: spin 2s linear infinite;
                margin: 30px auto;
            }
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>
    </head>
    <body>
        <h2>Переадресація до адміністративної панелі...</h2>
        <div class="loader"></div>
        <p>Якщо перенаправлення не відбувається автоматично, <a href="admin.php?token=<?php echo $auth_token; ?>">натисніть тут</a></p>
        
        <script>
            // Автоматичне перенаправлення з деякою затримкою
            setTimeout(function() {
                window.location.href = 'admin.php?token=<?php echo $auth_token; ?>';
            }, 1500);
        </script>
    </body>
    </html>
    <?php
    exit;
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
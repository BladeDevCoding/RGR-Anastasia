<?php
// Налаштування сесії для роботи з Vercel
ini_set('session.cookie_secure', '0');
ini_set('session.cookie_httponly', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.cookie_samesite', 'None');
ini_set('session.cookie_lifetime', '86400');
session_start();

// Очистка всех данных сессии
$_SESSION = array();

// Уничтожение сессии
session_destroy();

// Перенаправление на страницу входа з параметром явного виходу
header('Location: login.php?logout=1');
exit;
?>
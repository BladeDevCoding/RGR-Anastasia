<?php
// Налаштування сесії для роботи з Vercel
ini_set('session.cookie_secure', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.cookie_lifetime', '3600'); // 1 година
session_start();

// Очистка всех данных сессии
$_SESSION = array();

// Уничтожение сессии
session_destroy();

// Перенаправление на страницу входа
header('Location: login.php');
exit;
?>
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

// Удаляем котика
if (deleteKotyk($kotyk_id)) {
    // Удаляем фото, если оно существует
    $photo_path = 'images/cats/' . $kotyk_id . '.jpg';
    if (file_exists($photo_path)) {
        unlink($photo_path);
    }
    
    header('Location: admin.php?section=kotyky&success=1');
} else {
    header('Location: admin.php?section=kotyky&error=1');
}
exit;
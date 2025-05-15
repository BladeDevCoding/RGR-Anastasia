<?php
require_once 'db_functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admin.php?section=deserty');
    exit;
}

$desert_id = intval($_GET['id']);
$desert = getDesertById($desert_id);

if (!$desert) {
    header('Location: admin.php?section=deserty');
    exit;
}

// Удаляем десерт
if (deleteDesert($desert_id)) {
    // Удаляем фото, если оно существует
    $photo_path = 'images/deserts/' . $desert_id . '.jpg';
    if (file_exists($photo_path)) {
        unlink($photo_path);
    }
    
    header('Location: admin.php?section=deserty&success=1');
} else {
    header('Location: admin.php?section=deserty&error=1');
}
exit;
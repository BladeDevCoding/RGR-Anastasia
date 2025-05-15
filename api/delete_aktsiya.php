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

// Удаляем акцию
if (deleteAktsiya($aktsiya_id)) {
    header('Location: admin.php?section=aktsiyi&success=1');
} else {
    header('Location: admin.php?section=aktsiyi&error=1');
}
exit;
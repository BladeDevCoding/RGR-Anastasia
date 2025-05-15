<?php
require_once 'db_functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admin.php?section=kavarni');
    exit;
}

$kavyarnya_id = intval($_GET['id']);
$kavyarnya = getKavyarnyaById($kavyarnya_id);

if (!$kavyarnya) {
    header('Location: admin.php?section=kavarni');
    exit;
}

// Удаляем кав'ярню
if (deleteKavyarnya($kavyarnya_id)) {
    header('Location: admin.php?section=kavarni&success=1');
} else {
    header('Location: admin.php?section=kavarni&error=1');
}
exit;
<?php
require_once 'db_functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admin.php?section=ingredients');
    exit;
}

$ingredient_id = intval($_GET['id']);
$ingredient = getIngredientById($ingredient_id);

if (!$ingredient) {
    header('Location: admin.php?section=ingredients');
    exit;
}

// Удаляем ингредиент
if (deleteIngredient($ingredient_id)) {
    header('Location: admin.php?section=ingredients&success=1');
} else {
    header('Location: admin.php?section=ingredients&error=1');
}
exit;
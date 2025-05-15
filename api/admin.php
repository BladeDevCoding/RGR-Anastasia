<?php
session_start();
require_once 'db_functions.php';

// Проверка авторизации
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Если пользователь не авторизован, перенаправляем на страницу входа
    header('Location: login.php');
    exit;
}

// Получаем данные для административной панели
$kava_list = getAllKava();
$deserty_list = getAllDeserty();
$kavarni_list = getAllKavyarni();
$kotyky_list = getAllKotyky();
$aktsiyi_list = getAllAktsiyi();

// Определяем текущий раздел админки
$section = isset($_GET['section']) ? $_GET['section'] : 'kava';
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KityKoffe - Адміністративна панель</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        .admin-nav {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .admin-nav a {
            padding: 8px 15px;
            background-color: #f5f5f5;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
        }
        .admin-nav a.active {
            background-color: #795548;
            color: white;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #795548;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .btn-add {
            background-color: #4CAF50;
        }
        .btn-edit {
            background-color: #2196F3;
        }
        .btn-delete {
            background-color: #f44336;
        }
        .btn-view {
            background-color: #9E9E9E;
        }
        .actions {
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="admin-container">
        <div class="admin-header">
            <h1>Адміністративна панель</h1>
            <div>
                <a href="index.php" class="btn">На головну</a>
                <a href="logout.php" class="btn btn-delete">Вийти</a>
            </div>
        </div>
        
        <div class="admin-nav">
            <a href="admin.php?section=kava" <?php if($section == 'kava') echo 'class="active"'; ?>>Кава</a>
            <a href="admin.php?section=deserty" <?php if($section == 'deserty') echo 'class="active"'; ?>>Десерти</a>
            <a href="admin.php?section=kavarni" <?php if($section == 'kavarni') echo 'class="active"'; ?>>Кав'ярні</a>
            <a href="admin.php?section=kotyky" <?php if($section == 'kotyky') echo 'class="active"'; ?>>Котики</a>
            <a href="admin.php?section=aktsiyi" <?php if($section == 'aktsiyi') echo 'class="active"'; ?>>Акції</a>
            <a href="admin.php?section=ingredients" <?php if($section == 'ingredients') echo 'class="active"'; ?>>Інгредієнти</a>
        </div>
        
        <?php if($section == 'kava'): ?>
            <h2>Управління кавою</h2>
            <p>
                <a href="add_kava.php" class="btn btn-add">Додати новий вид кави</a>
            </p>
            
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Назва</th>
                        <th>Ціна (грн)</th>
                        <th>Час приготування (сек)</th>
                        <th>Доступність</th>
                        <th>Дії</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($kava_list) > 0): ?>
                        <?php foreach ($kava_list as $kava): ?>
                            <tr>
                                <td><?php echo $kava['id']; ?></td>
                                <td><?php echo htmlspecialchars($kava['nazva']); ?></td>
                                <td><?php echo number_format($kava['tsina'], 2); ?></td>
                                <td><?php echo $kava['chas_prihotuvannya']; ?></td>
                                <td><?php echo $kava['dostupna'] ? 'Так' : 'Ні'; ?></td>
                                <td class="actions">
                                    <a href="view_kava.php?id=<?php echo $kava['id']; ?>" class="btn btn-view">Деталі</a>
                                    <a href="edit_kava.php?id=<?php echo $kava['id']; ?>" class="btn btn-edit">Редагувати</a>
                                    <a href="delete_kava.php?id=<?php echo $kava['id']; ?>" class="btn btn-delete" onclick="return confirm('Ви впевнені, що хочете видалити цей вид кави?');">Видалити</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">Немає доступних видів кави</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        
        <?php elseif($section == 'deserty'): ?>
            <h2>Управління десертами</h2>
            <p>
                <a href="add_desert.php" class="btn btn-add">Додати новий десерт</a>
            </p>
            
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Назва</th>
                        <th>Ціна (грн)</th>
                        <th>Вага (г)</th>
                        <th>Категорія</th>
                        <th>Доступність</th>
                        <th>Дії</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($deserty_list) > 0): ?>
                        <?php foreach ($deserty_list as $desert): ?>
                            <tr>
                                <td><?php echo $desert['id']; ?></td>
                                <td><?php echo htmlspecialchars($desert['nazva']); ?></td>
                                <td><?php echo number_format($desert['tsina'], 2); ?></td>
                                <td><?php echo $desert['vaha_gram']; ?></td>
                                <td><?php echo htmlspecialchars($desert['kategoria']); ?></td>
                                <td><?php echo $desert['dostupnyy'] ? 'Так' : 'Ні'; ?></td>
                                <td class="actions">
                                    <a href="view_desert.php?id=<?php echo $desert['id']; ?>" class="btn btn-view">Деталі</a>
                                    <a href="edit_desert.php?id=<?php echo $desert['id']; ?>" class="btn btn-edit">Редагувати</a>
                                    <a href="delete_desert.php?id=<?php echo $desert['id']; ?>" class="btn btn-delete" onclick="return confirm('Ви впевнені, що хочете видалити цей десерт?');">Видалити</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">Немає доступних десертів</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
        <?php elseif($section == 'kavarni'): ?>
            <h2>Управління кав'ярнями</h2>
            <p>
                <a href="add_kavyarnya.php" class="btn btn-add">Додати нову кав'ярню</a>
            </p>
            
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Назва</th>
                        <th>Адреса</th>
                        <th>Телефон</th>
                        <th>Графік роботи</th>
                        <th>Дії</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($kavarni_list) > 0): ?>
                        <?php foreach ($kavarni_list as $kavyarnya): ?>
                            <tr>
                                <td><?php echo $kavyarnya['id']; ?></td>
                                <td><?php echo htmlspecialchars($kavyarnya['nazva']); ?></td>
                                <td><?php echo htmlspecialchars($kavyarnya['adresa']); ?></td>
                                <td><?php echo htmlspecialchars($kavyarnya['telefon']); ?></td>
                                <td><?php echo htmlspecialchars($kavyarnya['grafik_roboty']); ?></td>
                                <td class="actions">
                                    <a href="view_kavyarnya.php?id=<?php echo $kavyarnya['id']; ?>" class="btn btn-view">Деталі</a>
                                    <a href="edit_kavyarnya.php?id=<?php echo $kavyarnya['id']; ?>" class="btn btn-edit">Редагувати</a>
                                    <a href="delete_kavyarnya.php?id=<?php echo $kavyarnya['id']; ?>" class="btn btn-delete" onclick="return confirm('Ви впевнені, що хочете видалити цю кав\'ярню?');">Видалити</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">Немає доступних кав'ярень</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
        <?php elseif($section == 'kotyky'): ?>
            <h2>Управління котиками</h2>
            <p>
                <a href="add_kotyk.php" class="btn btn-add">Додати нового котика</a>
            </p>
            
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ім'я</th>
                        <th>Вік</th>
                        <th>Стать</th>
                        <th>Порода</th>
                        <th>Кав'ярня</th>
                        <th>Дії</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($kotyky_list) > 0): ?>
                        <?php foreach ($kotyky_list as $kotyk): ?>
                            <?php $kavyarnya = getKavyarnyaById($kotyk['kav_yarnya_id']); ?>
                            <tr>
                                <td><?php echo $kotyk['id']; ?></td>
                                <td><?php echo htmlspecialchars($kotyk['imya']); ?></td>
                                <td><?php echo $kotyk['vik']; ?></td>
                                <td><?php echo htmlspecialchars($kotyk['stat']); ?></td>
                                <td><?php echo htmlspecialchars($kotyk['poroda']); ?></td>
                                <td><?php echo htmlspecialchars($kavyarnya['nazva']); ?></td>
                                <td class="actions">
                                    <a href="view_kotyk.php?id=<?php echo $kotyk['id']; ?>" class="btn btn-view">Деталі</a>
                                    <a href="edit_kotyk.php?id=<?php echo $kotyk['id']; ?>" class="btn btn-edit">Редагувати</a>
                                    <a href="delete_kotyk.php?id=<?php echo $kotyk['id']; ?>" class="btn btn-delete" onclick="return confirm('Ви впевнені, що хочете видалити цього котика?');">Видалити</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">Немає доступних котиків</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
        <?php elseif($section == 'aktsiyi'): ?>
            <h2>Управління акціями</h2>
            <p>
                <a href="add_aktsiya.php" class="btn btn-add">Додати нову акцію</a>
            </p>
            
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Назва</th>
                        <th>Знижка (%)</th>
                        <th>Початок</th>
                        <th>Закінчення</th>
                        <th>Кав'ярня</th>
                        <th>Дії</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($aktsiyi_list) > 0): ?>
                        <?php foreach ($aktsiyi_list as $aktsiya): ?>
                            <tr>
                                <td><?php echo $aktsiya['id']; ?></td>
                                <td><?php echo htmlspecialchars($aktsiya['nazva']); ?></td>
                                <td><?php echo $aktsiya['znyzhka']; ?></td>
                                <td><?php echo date('d.m.Y', strtotime($aktsiya['data_pochatku'])); ?></td>
                                <td><?php echo date('d.m.Y', strtotime($aktsiya['data_zakinchennya'])); ?></td>
                                <td>
                                    <?php 
                                    if ($aktsiya['kav_yarnya_id']) {
                                        $kavyarnya = getKavyarnyaById($aktsiya['kav_yarnya_id']);
                                        echo htmlspecialchars($kavyarnya['nazva']);
                                    } else {
                                        echo 'Всі кав\'ярні';
                                    }
                                    ?>
                                </td>
                                <td class="actions">
                                    <a href="view_aktsiya.php?id=<?php echo $aktsiya['id']; ?>" class="btn btn-view">Деталі</a>
                                    <a href="edit_aktsiya.php?id=<?php echo $aktsiya['id']; ?>" class="btn btn-edit">Редагувати</a>
                                    <a href="delete_aktsiya.php?id=<?php echo $aktsiya['id']; ?>" class="btn btn-delete" onclick="return confirm('Ви впевнені, що хочете видалити цю акцію?');">Видалити</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">Немає доступних акцій</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
        <?php elseif($section == 'ingredients'): ?>
            <h2>Управління інгредієнтами</h2>
            <p>
                <a href="add_ingredient.php" class="btn btn-add">Додати новий інгредієнт</a>
            </p>
            
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Назва</th>
                        <th>Одиниця виміру</th>
                        <th>Дії</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $ingredients = getAllIngredients();
                    if (count($ingredients) > 0): ?>
                        <?php foreach ($ingredients as $ingredient): ?>
                            <tr>
                                <td><?php echo $ingredient['id']; ?></td>
                                <td><?php echo htmlspecialchars($ingredient['nazva']); ?></td>
                                <td><?php echo htmlspecialchars($ingredient['odynytsya']); ?></td>
                                <td class="actions">
                                    <a href="edit_ingredient.php?id=<?php echo $ingredient['id']; ?>" class="btn btn-edit">Редагувати</a>
                                    <a href="delete_ingredient.php?id=<?php echo $ingredient['id']; ?>" class="btn btn-delete" onclick="return confirm('Ви впевнені, що хочете видалити цей інгредієнт?');">Видалити</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">Немає доступних інгредієнтів</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>
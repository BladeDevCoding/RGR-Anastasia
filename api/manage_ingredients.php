<?php
require_once 'db_functions.php';

$message = '';
$error = '';


$ingredienty = getAllIngredienty();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
      
        if ($_POST['action'] === 'add') {
            $nazva = trim($_POST['nazva']);
            $odynytsya = trim($_POST['odynytsya']);
            
            if (empty($nazva)) {
                $error = 'Назва інгредієнта обов\'язкова';
            } elseif (empty($odynytsya)) {
                $error = 'Одиниця виміру обов\'язкова';
            } else {
                $result = addIngredient($nazva, $odynytsya);
                if ($result) {
                    $message = 'Інгредієнт успішно додано!';
                  
                    $ingredienty = getAllIngredienty();
                } else {
                    $error = 'Помилка при додаванні інгредієнта';
                }
            }
        }
 
        elseif ($_POST['action'] === 'delete' && isset($_POST['id'])) {
            $id = intval($_POST['id']);
            $result = deleteIngredient($id);
            if ($result) {
                $message = 'Інгредієнт успішно видалено!';
               
                $ingredienty = getAllIngredienty();
            } else {
                $error = 'Помилка при видаленні інгредієнта';
            }
        }
       
        elseif ($_POST['action'] === 'edit' && isset($_POST['id'])) {
            $id = intval($_POST['id']);
            $nazva = trim($_POST['nazva']);
            $odynytsya = trim($_POST['odynytsya']);
            
            if (empty($nazva)) {
                $error = 'Назва інгредієнта обов\'язкова';
            } elseif (empty($odynytsya)) {
                $error = 'Одиниця виміру обов\'язкова';
            } else {
                $result = updateIngredient($id, $nazva, $odynytsya);
                if ($result) {
                    $message = 'Інгредієнт успішно оновлено!';
                   
                    $ingredienty = getAllIngredienty();
                } else {
                    $error = 'Помилка при оновленні інгредієнта';
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управління інгредієнтами</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        h1 {
            color: #5d4037;
            text-align: center;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn {
            display: inline-block;
            padding: 8px 12px;
            background-color: #795548;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            margin-right: 5px;
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
        .error {
            color: #f44336;
            margin-bottom: 15px;
        }
        .success {
            color: #4CAF50;
            margin-bottom: 15px;
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
        .actions {
            white-space: nowrap;
        }
        .edit-form {
            display: none;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .edit-form.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Управління інгредієнтами</h1>
        
        <p><a href="index.php" class="btn">← Повернутися до списку кави</a></p>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($message): ?>
            <div class="success"><?php echo $message; ?></div>
        <?php endif; ?>
        
  
        <h2>Додати новий інгредієнт</h2>
        <form method="post" action="">
            <input type="hidden" name="action" value="add">
            
            <div class="form-group">
                <label for="nazva">Назва інгредієнта *</label>
                <input type="text" id="nazva" name="nazva" required>
            </div>
            
            <div class="form-group">
                <label for="odynytsya">Одиниця виміру *</label>
                <input type="text" id="odynytsya" name="odynytsya" required>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-add">Додати інгредієнт</button>
            </div>
        </form>
        
   
        <div id="editForm" class="edit-form">
            <h2>Редагувати інгредієнт</h2>
            <form method="post" action="">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" id="edit_id" name="id" value="">
                
                <div class="form-group">
                    <label for="edit_nazva">Назва інгредієнта *</label>
                    <input type="text" id="edit_nazva" name="nazva" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_odynytsya">Одиниця виміру *</label>
                    <input type="text" id="edit_odynytsya" name="odynytsya" required>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-edit">Зберегти зміни</button>
                    <button type="button" class="btn" onclick="hideEditForm()">Скасувати</button>
                </div>
            </form>
        </div>
        

        <h2>Список інгредієнтів</h2>
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
                <?php if (count($ingredienty) > 0): ?>
                    <?php foreach ($ingredienty as $ingredient): ?>
                        <tr>
                            <td><?php echo $ingredient['id']; ?></td>
                            <td><?php echo htmlspecialchars($ingredient['nazva']); ?></td>
                            <td><?php echo htmlspecialchars($ingredient['odynytsya']); ?></td>
                            <td class="actions">
                                <button class="btn btn-edit" onclick="showEditForm(<?php echo $ingredient['id']; ?>, '<?php echo addslashes(htmlspecialchars($ingredient['nazva'])); ?>', '<?php echo addslashes(htmlspecialchars($ingredient['odynytsya'])); ?>')">Редагувати</button>
                                
                                <form method="post" action="" style="display: inline;" onsubmit="return confirm('Ви впевнені, що хочете видалити цей інгредієнт?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $ingredient['id']; ?>">
                                    <button type="submit" class="btn btn-delete">Видалити</button>
                                </form>
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
    </div>
    
    <script>
        function showEditForm(id, nazva, odynytsya) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nazva').value = nazva;
            document.getElementById('edit_odynytsya').value = odynytsya;
            document.getElementById('editForm').classList.add('active');
            window.scrollTo(0, document.getElementById('editForm').offsetTop - 20);
        }
        
        function hideEditForm() {
            document.getElementById('editForm').classList.remove('active');
        }
    </script>
</body>
</html>
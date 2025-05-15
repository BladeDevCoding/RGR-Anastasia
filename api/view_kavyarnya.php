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

// Получаем список котиков, которые живут в этой кофейне
$kotyky = getKotykyByKavyarnyaId($kavyarnya_id);

$success = isset($_GET['success']) && $_GET['success'] == 1;
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($kavyarnya['nazva']); ?> - KityKoffe</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .details-container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .details-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        .details-section {
            margin-bottom: 30px;
        }
        .details-section h2 {
            color: #795548;
            margin-bottom: 15px;
        }
        .details-grid {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: 10px;
        }
        .details-label {
            font-weight: bold;
        }
        .kavyarnya-image {
            max-width: 100%;
            border-radius: 5px;
            margin-top: 20px;
        }
        .kotyky-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .kotyk-card {
            background-color: #f9f9f9;
            border-radius: 5px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .kotyk-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .success-message {
            background-color: #dff0d8;
            color: #3c763d;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="details-container">
        <?php if ($success): ?>
            <div class="success-message">Інформацію про кав'ярню успішно оновлено!</div>
        <?php endif; ?>
        
        <div class="details-header">
            <h1><?php echo htmlspecialchars($kavyarnya['nazva']); ?></h1>
            <div>
                <a href="admin.php?section=kavarni" class="btn btn-secondary">← Повернутися до списку</a>
                <a href="edit_kavyarnya.php?id=<?php echo $kavyarnya_id; ?>" class="btn btn-edit">Редагувати</a>
                <a href="delete_kavyarnya.php?id=<?php echo $kavyarnya_id; ?>" class="btn btn-delete" onclick="return confirm('Ви впевнені, що хочете видалити цю кав\'ярню?');">Видалити</a>
            </div>
        </div>
        
        <div class="details-section">
            <h2>Інформація про кав'ярню</h2>
            <div class="details-grid">
                <div class="details-label">Назва:</div>
                <div><?php echo htmlspecialchars($kavyarnya['nazva']); ?></div>
                
                <div class="details-label">Адреса:</div>
                <div><?php echo htmlspecialchars($kavyarnya['adresa']); ?></div>
                
                <div class="details-label">Телефон:</div>
                <div><?php echo htmlspecialchars($kavyarnya['telefon']); ?></div>
                
                <div class="details-label">Графік роботи:</div>
                <div><?php echo htmlspecialchars($kavyarnya['grafik_roboty']); ?></div>
            </div>
            
            <?php if (!empty($kavyarnya['opys'])): ?>
                <div class="details-label" style="margin-top: 15px;">Опис:</div>
                <div><?php echo nl2br(htmlspecialchars($kavyarnya['opys'])); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($kavyarnya["foto_url"])): ?>
                <img src="<?php echo htmlspecialchars($kavyarnya["foto_url"]); ?>" alt="<?php echo htmlspecialchars($kavyarnya["nazva"]); ?>" class="kavyarnya-image">
            <?php else: ?>
                <img src="images/kavarni/<?php echo $kavyarnya_id; ?>.jpg" alt="<?php echo htmlspecialchars($kavyarnya["nazva"]); ?>" class="kavyarnya-image" onerror="this.src='https://via.placeholder.com/400x300?text=Кав\'ярня'">
            <?php endif; ?>
        </div>
        
        <div class="details-section">
            <h2>Котики в цій кав'ярні</h2>
            <?php if (count($kotyky) > 0): ?>
                <div class="kotyky-list">
                    <?php foreach ($kotyky as $kotyk): ?>
                        <div class="kotyk-card">
                            <?php if (file_exists('images/cats/' . $kotyk['id'] . '.jpg')): ?>
                                <img src="images/cats/<?php echo $kotyk['id']; ?>.jpg" alt="<?php echo htmlspecialchars($kotyk['imya']); ?>" class="kotyk-image">
                            <?php endif; ?>
                            <h3><?php echo htmlspecialchars($kotyk['imya']); ?></h3>
                            <p><strong>Вік:</strong> <?php echo $kotyk['vik']; ?> років</p>
                            <p><strong>Порода:</strong> <?php echo htmlspecialchars($kotyk['poroda']); ?></p>
                            <a href="view_kotyk.php?id=<?php echo $kotyk['id']; ?>" class="btn btn-view">Деталі</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>У цій кав'ярні поки немає котиків.</p>
            <?php endif; ?>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>
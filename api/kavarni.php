<?php
require_once 'db_functions.php';

// Получаем данные о кофейнях
$kavarni = getAllKavyarni();

// Если запрошена конкретная кофейня
$kavyarnya_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$single_kavyarnya = null;
$kotyky_in_kavyarnya = [];

if ($kavyarnya_id > 0) {
    $single_kavyarnya = getKavyarnyaById($kavyarnya_id);
    if ($single_kavyarnya) {
        $kotyky_in_kavyarnya = getKotykyByKavyarnyaId($kavyarnya_id);
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $single_kavyarnya ? htmlspecialchars($single_kavyarnya['nazva']) : 'Наші кав\'ярні'; ?> - KityKoffe</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .kavarni-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .kavarni-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .kavarni-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
        }
        .kavyarnya-card {
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .kavyarnya-card:hover {
            transform: translateY(-5px);
        }
        .kavyarnya-image {
            height: 200px;
            overflow: hidden;
        }
        .kavyarnya-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .kavyarnya-info {
            padding: 20px;
        }
        .kavyarnya-title {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #5d4037;
        }
        .kavyarnya-details {
            margin-bottom: 15px;
        }
        .kavyarnya-details p {
            margin-bottom: 5px;
        }
        .kavyarnya-details strong {
            color: #795548;
        }
        .single-kavyarnya {
            background-color: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .single-kavyarnya-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .single-kavyarnya-image {
            max-height: 400px;
            overflow: hidden;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .single-kavyarnya-image img {
            width: 100%;
            object-fit: cover;
        }
        .kotyky-section {
            margin-top: 40px;
        }
        .kotyky-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .kotyk-card {
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .kotyk-card:hover {
            transform: translateY(-5px);
        }
        .kotyk-image {
            height: 200px;
            overflow: hidden;
        }
        .kotyk-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .kotyk-info {
            padding: 15px;
        }
        .back-link {
            margin-bottom: 20px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="kavarni-container">
        <?php if ($single_kavyarnya): ?>
            <!-- Детальная информация о конкретной кофейне -->
            <a href="kavarni.php" class="back-link"><< Всі кав'ярні</a>
            
            <div class="single-kavyarnya">
                <div class="single-kavyarnya-header">
                    <h1><?php echo htmlspecialchars($single_kavyarnya['nazva']); ?></h1>
                </div>
                
                <div class="single-kavyarnya-image">
                    <img src="images/cafes/<?php echo $single_kavyarnya['id']; ?>.jpg" alt="<?php echo htmlspecialchars($single_kavyarnya['nazva']); ?>" onerror="this.src='https://via.placeholder.com/800x400?text=Кав\'ярня'">
                </div>
                
                <div class="kavyarnya-details">
                    <p><strong>Адреса:</strong> <?php echo htmlspecialchars($single_kavyarnya['adresa']); ?></p>
                    <p><strong>Телефон:</strong> <?php echo htmlspecialchars($single_kavyarnya['telefon']); ?></p>
                    <p><strong>Графік роботи:</strong> <?php echo htmlspecialchars($single_kavyarnya['grafik_roboty']); ?></p>
                    <p><strong>Опис:</strong> <?php echo htmlspecialchars($single_kavyarnya['opys']); ?></p>
                </div>
            </div>
            
            <!-- Котики в этой кофейне -->
            <?php if (!empty($kotyky_in_kavyarnya)): ?>
            <div class="kotyky-section">
                <h2>Наші пухнасті мешканці</h2>
                <div class="kotyky-grid">
                    <?php foreach($kotyky_in_kavyarnya as $kotyk): ?>
                    <div class="kotyk-card">
                        <div class="kotyk-image">
                            <img src="images/cats/<?php echo $kotyk['id']; ?>.jpg" alt="<?php echo htmlspecialchars($kotyk['imya']); ?>" onerror="this.src='https://via.placeholder.com/300x300?text=Котик'">
                        </div>
                        <div class="kotyk-info">
                            <h3><?php echo htmlspecialchars($kotyk['imya']); ?></h3>
                            <p><strong>Вік:</strong> <?php echo $kotyk['vik']; ?> років</p>
                            <p><strong>Порода:</strong> <?php echo htmlspecialchars($kotyk['poroda']); ?></p>
                            <a href="kotyky.php?id=<?php echo $kotyk['id']; ?>" class="btn">Детальніше</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
        <?php else: ?>
            <!-- Список всех кофеен -->
            <div class="kavarni-header">
                <h1>Наші кав'ярні</h1>
                <p>Відвідайте наші затишні кав'ярні з котиками у різних куточках Черкас</p>
            </div>
            
            <div class="kavarni-grid">
                <?php foreach($kavarni as $kavyarnya): ?>
                <div class="kavyarnya-card">
                    <div class="kavyarnya-image">
                        <img src="images/cafes/<?php echo $kavyarnya['id']; ?>.jpg" alt="<?php echo htmlspecialchars($kavyarnya['nazva']); ?>" onerror="this.src='https://via.placeholder.com/350x200?text=Кав\'ярня'">
                    </div>
                    <div class="kavyarnya-info">
                        <h2 class="kavyarnya-title"><?php echo htmlspecialchars($kavyarnya['nazva']); ?></h2>
                        <div class="kavyarnya-details">
                            <p><strong>Адреса:</strong> <?php echo htmlspecialchars($kavyarnya['adresa']); ?></p>
                            <p><strong>Телефон:</strong> <?php echo htmlspecialchars($kavyarnya['telefon']); ?></p>
                            <p><strong>Графік роботи:</strong> <?php echo htmlspecialchars($kavyarnya['grafik_roboty']); ?></p>
                        </div>
                        <a href="kavarni.php?id=<?php echo $kavyarnya['id']; ?>" class="btn">Детальніше</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>
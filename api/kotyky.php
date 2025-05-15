<?php
require_once 'db_functions.php';

// Получаем данные о котиках
$kotyky = getAllKotyky();

// Если запрошен конкретный котик
$kotyk_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$single_kotyk = null;
$kavyarnya = null;

if ($kotyk_id > 0) {
    $single_kotyk = getKotykById($kotyk_id);
    if ($single_kotyk) {
        $kavyarnya = getKavyarnyaById($single_kotyk['kav_yarnya_id']);
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $single_kotyk ? htmlspecialchars($single_kotyk['imya']) : 'Наші котики'; ?> - KityKoffe</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .kotyky-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .kotyky-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .kotyky-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }
        .kotyk-card {
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .kotyk-card:hover {
            transform: translateY(-5px);
        }
        .kotyk-image {
            height: 250px;
            width: 100%;
            overflow: hidden;
        }
        .kotyk-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            max-width: 100%;
            display: block;
        }
        .kotyk-info {
            padding: 20px;
        }
        .kotyk-title {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #5d4037;
        }
        .kotyk-details {
            margin-bottom: 15px;
        }
        .kotyk-details p {
            margin-bottom: 5px;
        }
        .kotyk-details strong {
            color: #795548;
        }
        .single-kotyk {
            background-color: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .single-kotyk-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .single-kotyk-image {
            max-height: 500px;
            overflow: hidden;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .single-kotyk-image img {
            width: 100%;
            object-fit: cover;
        }
        .back-link {
            margin-bottom: 20px;
            display: inline-block;
        }
        .kotyk-kavyarnya {
            margin-top: 30px;
            padding: 20px;
            background-color: #f9f5f0;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="kotyky-container">
        <?php if ($single_kotyk): ?>
            <!-- Детальная информация о конкретном котике -->
            <a href="kotyky.php" class="back-link"><< Всі котики</a>
            
            <div class="single-kotyk">
                <div class="single-kotyk-header">
                    <h1><?php echo htmlspecialchars($single_kotyk['imya']); ?></h1>
                </div>
                
                <div class="single-kotyk-image">
                    <img src="images/cats/<?php echo $single_kotyk['id']; ?>.jpg" alt="<?php echo htmlspecialchars($single_kotyk['imya']); ?>" onerror="this.src='https://via.placeholder.com/800x500?text=Котик'">
                </div>
                
                <div class="kotyk-details">
                    <p><strong>Вік:</strong> <?php echo $single_kotyk['vik']; ?> років</p>
                    <p><strong>Стать:</strong> <?php echo htmlspecialchars($single_kotyk['stat']); ?></p>
                    <p><strong>Порода:</strong> <?php echo htmlspecialchars($single_kotyk['poroda']); ?></p>
                    <p><strong>Характеристика:</strong> <?php echo htmlspecialchars($single_kotyk['harakterystyka']); ?></p>
                </div>
                
                <?php if ($kavyarnya): ?>
                <div class="kotyk-kavyarnya">
                    <h3>Де можна зустріти <?php echo htmlspecialchars($single_kotyk['imya']); ?>?</h3>
                    <p>Цей котик мешкає в кав'ярні <strong><?php echo htmlspecialchars($kavyarnya['nazva']); ?></strong></p>
                    <p><strong>Адреса:</strong> <?php echo htmlspecialchars($kavyarnya['adresa']); ?></p>
                    <p><strong>Графік роботи:</strong> <?php echo htmlspecialchars($kavyarnya['grafik_roboty']); ?></p>
                    <a href="kavarni.php?id=<?php echo $kavyarnya['id']; ?>" class="btn">Відвідати кав'ярню</a>
                </div>
                <?php endif; ?>
            </div>
            
        <?php else: ?>
            <!-- Список всех котиков -->
            <div class="kotyky-header">
                <h1>Наші котики</h1>
                <p>Познайомтеся з пухнастими мешканцями наших кав'ярень</p>
            </div>
            
            <div class="kotyky-grid">
                <?php foreach($kotyky as $kotyk): ?>
                <div class="kotyk-card">
                    <div class="kotyk-image">
                        <img src="images/cats/<?php echo $kotyk['id']; ?>.jpg" alt="<?php echo htmlspecialchars($kotyk['imya']); ?>" onerror="this.src='https://via.placeholder.com/300x250?text=Котик'">
                    </div>
                    <div class="kotyk-info">
                        <h2 class="kotyk-title"><?php echo htmlspecialchars($kotyk['imya']); ?></h2>
                        <div class="kotyk-details">
                            <p><strong>Вік:</strong> <?php echo $kotyk['vik']; ?> років</p>
                            <p><strong>Порода:</strong> <?php echo htmlspecialchars($kotyk['poroda']); ?></p>
                            <?php 
                            $kavyarnya = getKavyarnyaById($kotyk['kav_yarnya_id']);
                            if ($kavyarnya): 
                            ?>
                            <p><strong>Кав'ярня:</strong> <?php echo htmlspecialchars($kavyarnya['nazva']); ?></p>
                            <?php endif; ?>
                        </div>
                        <a href="kotyky.php?id=<?php echo $kotyk['id']; ?>" class="btn">Познайомитись</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>
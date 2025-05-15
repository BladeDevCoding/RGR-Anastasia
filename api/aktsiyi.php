<?php
require_once 'db_functions.php';

// Получаем данные об акциях
$aktsiyi = getAllAktsiyi();

// Если запрошена конкретная акция
$aktsiya_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$single_aktsiya = null;
$kavyarnya = null;

if ($aktsiya_id > 0) {
    $single_aktsiya = getAktsiyaById($aktsiya_id);
    if ($single_aktsiya && $single_aktsiya['kav_yarnya_id']) {
        $kavyarnya = getKavyarnyaById($single_aktsiya['kav_yarnya_id']);
    }
}

// Фильтруем только активные акции для общего списка
$active_aktsiyi = getActiveAktsiyi();
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $single_aktsiya ? htmlspecialchars($single_aktsiya['nazva']) : 'Акції'; ?> - KityKoffe</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .aktsiyi-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .aktsiyi-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .aktsiyi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
        }
        .aktsiya-card {
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
            position: relative;
        }
        .aktsiya-card:hover {
            transform: translateY(-5px);
        }
        .aktsiya-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #e53935;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
        }
        .aktsiya-image {
            height: 200px;
            overflow: hidden;
        }
        .aktsiya-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .aktsiya-info {
            padding: 20px;
        }
        .aktsiya-title {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #5d4037;
        }
        .aktsiya-details {
            margin-bottom: 15px;
        }
        .aktsiya-details p {
            margin-bottom: 5px;
        }
        .aktsiya-details strong {
            color: #795548;
        }
        .single-aktsiya {
            background-color: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            position: relative;
        }
        .single-aktsiya-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .single-aktsiya-image {
            max-height: 400px;
            overflow: hidden;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .single-aktsiya-image img {
            width: 100%;
            object-fit: cover;
        }
        .back-link {
            margin-bottom: 20px;
            display: inline-block;
        }
        .aktsiya-kavyarnya {
            margin-top: 30px;
            padding: 20px;
            background-color: #f9f5f0;
            border-radius: 8px;
        }
        .aktsiya-dates {
            display: inline-block;
            background-color: #f5f5f5;
            padding: 10px 15px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .discount-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #e53935;
            color: white;
            padding: 10px 15px;
            border-radius: 50%;
            font-size: 1.2rem;
            font-weight: bold;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="aktsiyi-container">
        <?php if ($single_aktsiya): ?>
            <!-- Детальная информация о конкретной акции -->
            <a href="aktsiyi.php" class="back-link"><< Всі акції</a>
            
            <div class="single-aktsiya">
                <div class="single-aktsiya-header">
                    <h1><?php echo htmlspecialchars($single_aktsiya['nazva']); ?></h1>
                    <div class="discount-badge">-<?php echo $single_aktsiya['znyzhka']; ?>%</div>
                </div>
                
                <div class="single-aktsiya-image">
                    <img src="images/promotions/<?php echo $single_aktsiya['id']; ?>.jpg" alt="<?php echo htmlspecialchars($single_aktsiya['nazva']); ?>" onerror="this.src='https://via.placeholder.com/800x400?text=Акція'">
                </div>
                
                <div class="aktsiya-details">
                    <p><?php echo htmlspecialchars($single_aktsiya['opis']); ?></p>
                    
                    <div class="aktsiya-dates">
                        <p><strong>Період дії:</strong> з <?php echo date('d.m.Y', strtotime($single_aktsiya['data_pochatku'])); ?> 
                        по <?php echo date('d.m.Y', strtotime($single_aktsiya['data_zakinchennya'])); ?></p>
                    </div>
                </div>
                
                <?php if ($kavyarnya): ?>
                <div class="aktsiya-kavyarnya">
                    <h3>Де діє акція?</h3>
                    <p>Ця акція діє в кав'ярні <strong><?php echo htmlspecialchars($kavyarnya['nazva']); ?></strong></p>
                    <p><strong>Адреса:</strong> <?php echo htmlspecialchars($kavyarnya['adresa']); ?></p>
                    <p><strong>Графік роботи:</strong> <?php echo htmlspecialchars($kavyarnya['grafik_roboty']); ?></p>
                    <a href="kavarni.php?id=<?php echo $kavyarnya['id']; ?>" class="btn">Відвідати кав'ярню</a>
                </div>
                <?php else: ?>
                <div class="aktsiya-kavyarnya">
                    <h3>Де діє акція?</h3>
                    <p>Ця акція діє у <strong>всіх кав'ярнях</strong> мережі KityKoffe!</p>
                    <a href="kavarni.php" class="btn">Переглянути всі кав'ярні</a>
                </div>
                <?php endif; ?>
            </div>
            
        <?php else: ?>
            <!-- Список всех акций -->
            <div class="aktsiyi-header">
                <h1>Акції та спеціальні пропозиції</h1>
                <p>Скористайтеся нашими вигідними пропозиціями та знижками</p>
            </div>
            
            <div class="aktsiyi-grid">
                <?php if (count($active_aktsiyi) > 0): ?>
                    <?php foreach($active_aktsiyi as $aktsiya): ?>
                    <div class="aktsiya-card">
                        <div class="aktsiya-badge">-<?php echo $aktsiya['znyzhka']; ?>%</div>
                        <div class="aktsiya-image">
                            <img src="images/promotions/<?php echo $aktsiya['id']; ?>.jpg" alt="<?php echo htmlspecialchars($aktsiya['nazva']); ?>" onerror="this.src='https://via.placeholder.com/350x200?text=Акція'">
                        </div>
                        <div class="aktsiya-info">
                            <h2 class="aktsiya-title"><?php echo htmlspecialchars($aktsiya['nazva']); ?></h2>
                            <div class="aktsiya-details">
                                <p><?php echo htmlspecialchars(substr($aktsiya['opis'], 0, 100)) . '...'; ?></p>
                                <p><strong>Діє до:</strong> <?php echo date('d.m.Y', strtotime($aktsiya['data_zakinchennya'])); ?></p>
                                <?php 
                                if ($aktsiya['kav_yarnya_id']) {
                                    $kavyarnya = getKavyarnyaById($aktsiya['kav_yarnya_id']);
                                    if ($kavyarnya): 
                                    ?>
                                    <p><strong>Кав'ярня:</strong> <?php echo htmlspecialchars($kavyarnya['nazva']); ?></p>
                                    <?php endif; 
                                } else { ?>
                                    <p><strong>Діє у всіх кав'ярнях</strong></p>
                                <?php } ?>
                            </div>
                            <a href="aktsiyi.php?id=<?php echo $aktsiya['id']; ?>" class="btn">Детальніше</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-aktsiyi">
                        <p>На даний момент активних акцій немає. Заходьте пізніше!</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>
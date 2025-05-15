<?php
require_once 'db_functions.php';

// Получаем данные для страницы меню
$kava_list = getAllKava();
$deserty_list = getAllDeserty();

// Фильтрация по категории десертов (если выбрана)
$selected_category = isset($_GET['category']) ? $_GET['category'] : '';
if ($selected_category) {
    $filtered_deserty = [];
    foreach ($deserty_list as $desert) {
        if ($desert['kategoria'] == $selected_category) {
            $filtered_deserty[] = $desert;
        }
    }
    $deserty_list = $filtered_deserty;
}

// Получаем уникальные категории десертов
$categories = [];
$all_deserty = getAllDeserty();
foreach ($all_deserty as $desert) {
    if (!in_array($desert['kategoria'], $categories) && $desert['kategoria']) {
        $categories[] = $desert['kategoria'];
    }
}
sort($categories);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Меню - KityKoffe</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .menu-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .menu-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .menu-section {
            margin-bottom: 50px;
        }
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .menu-item {
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .menu-item:hover {
            transform: translateY(-5px);
        }
        .menu-item-image {
            height: 200px;
            overflow: hidden;
        }
        .menu-item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .menu-item-info {
            padding: 15px;
        }
        .menu-item-title {
            font-size: 1.2rem;
            margin-bottom: 10px;
            color: #5d4037;
        }
        .menu-item-price {
            font-weight: bold;
            color: #795548;
            margin-bottom: 10px;
        }
        .menu-item-desc {
            color: #666;
            margin-bottom: 15px;
            font-size: 0.9rem;
        }
        .category-filter {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }
        .category-filter a {
            padding: 8px 15px;
            background-color: #f5f5f5;
            border-radius: 20px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s;
        }
        .category-filter a:hover, .category-filter a.active {
            background-color: #795548;
            color: white;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="menu-container">
        <div class="menu-header">
            <h1>Наше меню</h1>
            <p>Насолоджуйтесь найкращими кавовими напоями та десертами</p>
        </div>
        
        <!-- Секция с кофе -->
        <div class="menu-section">
            <h2>Кавові напої</h2>
            <div class="menu-grid">
                <?php foreach($kava_list as $kava): ?>
                    <?php if($kava['dostupna']): ?>
                    <div class="menu-item">
                        <div class="menu-item-info">
                            <h3 class="menu-item-title"><?php echo htmlspecialchars($kava['nazva']); ?></h3>
                            <p class="menu-item-price"><?php echo number_format($kava['tsina'], 2); ?> грн</p>
                            <p class="menu-item-desc"><?php echo htmlspecialchars($kava['opis'] ?? 'Смачний кавовий напій, приготований з любов\'ю'); ?></p>
                            <p><small>Час приготування: <?php echo $kava['chas_prihotuvannya']; ?> сек</small></p>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Секция с десертами -->
        <div class="menu-section">
            <h2>Десерти</h2>
            
            <!-- Фильтр по категориям -->
            <div class="category-filter">
                <a href="menu.php" <?php if(!$selected_category) echo 'class="active"'; ?>>Всі десерти</a>
                <?php foreach($categories as $category): ?>
                <a href="menu.php?category=<?php echo urlencode($category); ?>" <?php if($selected_category == $category) echo 'class="active"'; ?>>
                    <?php echo htmlspecialchars($category); ?>
                </a>
                <?php endforeach; ?>
            </div>
            
            <div class="menu-grid">
                <?php if(count($deserty_list) > 0): ?>
                    <?php foreach($deserty_list as $desert): ?>
                        <?php if($desert['dostupnyy']): ?>
                        <div class="menu-item">
                            <div class="menu-item-image">
                                <img src="images/desserts/<?php echo $desert['id']; ?>.jpg" alt="<?php echo htmlspecialchars($desert['nazva']); ?>" onerror="this.src='https://via.placeholder.com/300x200?text=Десерт'">
                            </div>
                            <div class="menu-item-info">
                                <h3 class="menu-item-title"><?php echo htmlspecialchars($desert['nazva']); ?></h3>
                                <p class="menu-item-price"><?php echo number_format($desert['tsina'], 2); ?> грн</p>
                                <p class="menu-item-desc"><?php echo htmlspecialchars($desert['opis'] ?? 'Смачний десерт, який чудово доповнить вашу каву'); ?></p>
                                <p><small>Вага: <?php echo $desert['vaha_gram']; ?> г</small></p>
                                <?php if($desert['kategoria']): ?>
                                <p><small>Категорія: <?php echo htmlspecialchars($desert['kategoria']); ?></small></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>На жаль, десерти в цій категорії тимчасово відсутні.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
</body>
</html>
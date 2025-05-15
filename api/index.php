<?php
require_once 'db_functions.php';

// Получаем данные для главной страницы
$kavarni = getAllKavyarni();
$aktsiyi = getActiveAktsiyi();
$randomKotyky = getRandomKotyky(3); // Получаем 3 случайных котика для отображения
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KityKoffe - Мережа кав'ярень з котами</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <main>
        <!-- Секция с баннером -->
        <section class="hero">
            <div class="container">
                <h1>Ласкаво просимо до KityKoffe!</h1>
                <p class="hero-text">Насолоджуйтесь смачною кавою в компанії пухнастих друзів</p>
                <a href="menu.php" class="btn">Переглянути меню</a>
            </div>
        </section>
        
        <!-- О нас -->
        <section class="about">
            <div class="container">
                <h2>Про нас</h2>
                <div class="about-content">
                    <div class="about-text">
                        <p>KityKoffe - це унікальна мережа кав'ярень у Черкасах, де ви можете насолодитися чашкою ароматної кави в компанії милих котиків.</p>
                        <p>Наші кав'ярні - це місце, де створюється особлива атмосфера затишку та спокою. Тут можна відпочити від міської метушні, попрацювати за ноутбуком або просто провести час з друзями.</p>
                        <p>Ми пропонуємо широкий вибір кавових напоїв та смачних десертів, а наші пухнасті мешканці завжди раді новим знайомствам!</p>
                    </div>
                    <div class="about-image">
                        <img src="images/cafe-interior.jpg" alt="Інтер'єр кав'ярні" onerror="this.src='https://via.placeholder.com/400x300?text=KityKoffe'">
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Наши кофейни -->
        <section class="featured-kavarni">
            <div class="container">
                <h2>Наші кав'ярні</h2>
                <div class="kavarni-grid">
                    <?php foreach(array_slice($kavarni, 0, 3) as $kavyarnya): ?>
                    <div class="kavyarnya-card">
                        <h3><?php echo htmlspecialchars($kavyarnya['nazva']); ?></h3>
                        <p><strong>Адреса:</strong> <?php echo htmlspecialchars($kavyarnya['adresa']); ?></p>
                        <p><strong>Графік:</strong> <?php echo htmlspecialchars($kavyarnya['grafik_roboty']); ?></p>
                        <p><?php echo htmlspecialchars(substr($kavyarnya['opys'], 0, 100)) . '...'; ?></p>
                        <a href="kavarni.php?id=<?php echo $kavyarnya['id']; ?>" class="btn">Детальніше</a>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="center">
                    <a href="kavarni.php" class="btn">Всі кав'ярні</a>
                </div>
            </div>
        </section>
        
        <!-- Наши котики -->
        <section class="featured-kotyky">
            <div class="container">
                <h2>Наші котики</h2>
                <div class="kotyky-grid">
                    <?php foreach($randomKotyky as $kotyk): ?>
                    <div class="kotyk-card">
                        <div class="kotyk-image">
                            <img src="images/cats/<?php echo $kotyk['id']; ?>.jpg" alt="<?php echo htmlspecialchars($kotyk['imya']); ?>" onerror="this.src='https://via.placeholder.com/300x300?text=Котик'">
                        </div>
                        <h3><?php echo htmlspecialchars($kotyk['imya']); ?></h3>
                        <p><strong>Вік:</strong> <?php echo $kotyk['vik']; ?> років</p>
                        <p><strong>Порода:</strong> <?php echo htmlspecialchars($kotyk['poroda']); ?></p>
                        <a href="kotyky.php?id=<?php echo $kotyk['id']; ?>" class="btn">Познайомитись</a>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="center">
                    <a href="kotyky.php" class="btn">Всі котики</a>
                </div>
            </div>
        </section>
        
        <!-- Акции -->
        <?php if(!empty($aktsiyi)): ?>
        <section class="featured-aktsiyi">
            <div class="container">
                <h2>Поточні акції</h2>
                <div class="aktsiyi-grid">
                    <?php foreach(array_slice($aktsiyi, 0, 3) as $aktsiya): ?>
                    <div class="aktsiya-card">
                        <h3><?php echo htmlspecialchars($aktsiya['nazva']); ?></h3>
                        <p><?php echo htmlspecialchars(substr($aktsiya['opis'], 0, 100)) . '...'; ?></p>
                        <p><strong>Знижка:</strong> <?php echo $aktsiya['znyzhka']; ?>%</p>
                        <p><strong>Діє до:</strong> <?php echo date('d.m.Y', strtotime($aktsiya['data_zakinchennya'])); ?></p>
                        <a href="aktsiyi.php?id=<?php echo $aktsiya['id']; ?>" class="btn">Детальніше</a>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="center">
                    <a href="aktsiyi.php" class="btn">Всі акції</a>
                </div>
            </div>
        </section>
        <?php endif; ?>
    </main>
    
    <?php include 'footer.php'; ?>
</body>
</html>
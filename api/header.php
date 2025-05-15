<?php
// Определяем текущую страницу для активного пункта меню
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- Подключаем Font Awesome для иконок -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<header>
    <div class="container header-container">
        <div class="logo">
            <a href="index.php"><img src="foto/logo.png" alt="Логотип" class="logo-image" style="width:30px; height:30px; display:inline-block; vertical-align:middle;" onerror="console.log('Ошибка загрузки логотипа'); this.onerror=null; this.src='https://via.placeholder.com/30x30?text=KK'"> KityKoffe</a>
        </div>

        <nav>
            <ul>
                <li><a href="index.php" <?php if($current_page == 'index.php') echo 'class="active"'; ?>>Головна</a></li>
                <li><a href="menu.php" <?php if($current_page == 'menu.php') echo 'class="active"'; ?>>Меню</a></li>
                <li><a href="kavarni.php" <?php if($current_page == 'kavarni.php') echo 'class="active"'; ?>>Кав'ярні</a></li>
                <li><a href="kotyky.php" <?php if($current_page == 'kotyky.php') echo 'class="active"'; ?>>Котики</a></li>
                <li><a href="aktsiyi.php" <?php if($current_page == 'aktsiyi.php') echo 'class="active"'; ?>>Акції</a></li>
                <li><a href="admin.php" <?php if($current_page == 'admin.php') echo 'class="active"'; ?>>Адмін</a></li>
            </ul>
        </nav>
        <div class="accessibility-controls">
            <button id="colorblind-toggle" class="accessibility-btn" title="Режим для дальтоників"><i class="fa fa-eye"></i></button>
            <button id="font-size-increase" class="accessibility-btn" title="Збільшити розмір шрифту"><i class="fa fa-plus"></i></button>
            <button id="font-size-decrease" class="accessibility-btn" title="Зменшити розмір шрифту"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <script>
        // Функция для переключения режима для дальтоников
        document.getElementById('colorblind-toggle').addEventListener('click', function() {
            document.body.classList.toggle('colorblind-mode');
        });
        
        // Функция для увеличения размера шрифта
        document.getElementById('font-size-increase').addEventListener('click', function() {
            let currentSize = parseFloat(getComputedStyle(document.documentElement).fontSize);
            document.documentElement.style.fontSize = (currentSize + 2) + 'px';
        });
        
        // Функция для уменьшения размера шрифта
        document.getElementById('font-size-decrease').addEventListener('click', function() {
            let currentSize = parseFloat(getComputedStyle(document.documentElement).fontSize);
            if (currentSize > 12) { // Минимальный размер шрифта
                document.documentElement.style.fontSize = (currentSize - 2) + 'px';
            }
        });
        
        // Проверка загрузки логотипа
        document.addEventListener('DOMContentLoaded', function() {
            var logoImg = document.querySelector('.logo-image');
            if (logoImg) {
                console.log('Путь к логотипу:', logoImg.src);
                // Проверяем, загрузилось ли изображение
                if (!logoImg.complete || logoImg.naturalWidth === 0) {
                    console.log('Логотип не загрузился, устанавливаем запасное изображение');
                    logoImg.src = 'https://via.placeholder.com/30x30?text=KK';
                }
            }
        });
    </script>
</header>
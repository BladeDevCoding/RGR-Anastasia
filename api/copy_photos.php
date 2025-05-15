<?php
// Скрипт для копирования фотографий из исходных папок в новые папки

// Создаем директории, если они не существуют
function createDirIfNotExists($dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
        echo "Создана директория: $dir<br>";
    }
}

// Копирование файла с проверкой
function copyFileWithCheck($source, $destination) {
    if (file_exists($source)) {
        if (copy($source, $destination)) {
            echo "Успешно скопирован файл из $source в $destination<br>";
            return true;
        } else {
            echo "<span style='color:red'>Ошибка при копировании файла из $source в $destination</span><br>";
            return false;
        }
    } else {
        echo "<span style='color:red'>Исходный файл не существует: $source</span><br>";
        return false;
    }
}

// Создаем необходимые директории
createDirIfNotExists(__DIR__ . '/foto_deserts');
createDirIfNotExists(__DIR__ . '/foto_kafe');
createDirIfNotExists(__DIR__ . '/images/deserts');
createDirIfNotExists(__DIR__ . '/images/kavarni');

// Копируем фотографии десертов
$desertPhotos = [
    ['source' => 'D:\\Documents\\server\\htdocs\\mysite\\РГР\\foto_deserts\\tiramisu.jpg', 'destination' => __DIR__ . '/foto_deserts/tiramisu.jpg'],
    ['source' => 'D:\\Documents\\server\\htdocs\\mysite\\РГР\\foto_deserts\\new_work_cheese cake.jpg', 'destination' => __DIR__ . '/foto_deserts/new_york_cheesecake.jpg'],
    ['source' => 'D:\\Documents\\server\\htdocs\\mysite\\РГР\\foto_deserts\\makaruns.jpg', 'destination' => __DIR__ . '/foto_deserts/makaruns.jpg'],
    ['source' => 'D:\\Documents\\server\\htdocs\\mysite\\РГР\\foto_deserts\\kruasan_migdal.jpg', 'destination' => __DIR__ . '/foto_deserts/kruasan_migdal.jpg'],
    ['source' => 'D:\\Documents\\server\\htdocs\\mysite\\РГР\\foto_deserts\\chocolate_fonda n.jpg', 'destination' => __DIR__ . '/foto_deserts/chocolate_fondan.jpg']
];

// Копируем фотографии кофеен
$kafePhotos = [
    ['source' => 'D:\\Documents\\server\\htdocs\\mysite\\PTP\\foto_kafe\\1.jpg', 'destination' => __DIR__ . '/foto_kafe/1.jpg'],
    ['source' => 'D:\\Documents\\server\\htdocs\\mysite\\PTP\\foto_kafe\\2.jpg', 'destination' => __DIR__ . '/foto_kafe/2.jpg'],
    ['source' => 'D:\\Documents\\server\\htdocs\\mysite\\PTP\\foto_kafe\\3.jpg', 'destination' => __DIR__ . '/foto_kafe/3.jpg'],
    ['source' => 'D:\\Documents\\server\\htdocs\\mysite\\PTP\\foto_kafe\\4.jpg', 'destination' => __DIR__ . '/foto_kafe/4.jpg'],
    ['source' => 'D:\\Documents\\server\\htdocs\\mysite\\PTP\\foto_kafe\\5.jpg', 'destination' => __DIR__ . '/foto_kafe/5.jpg']
];

// Копируем также в папку images для отображения на сайте
$desertImagesPhotos = [
    ['source' => 'D:\\Documents\\server\\htdocs\\mysite\\РГР\\foto_deserts\\tiramisu.jpg', 'destination' => __DIR__ . '/images/deserts/1.jpg'],
    ['source' => 'D:\\Documents\\server\\htdocs\\mysite\\РГР\\foto_deserts\\new_work_cheese cake.jpg', 'destination' => __DIR__ . '/images/deserts/2.jpg'],
    ['source' => 'D:\\Documents\\server\\htdocs\\mysite\\РГР\\foto_deserts\\makaruns.jpg', 'destination' => __DIR__ . '/images/deserts/3.jpg'],
    ['source' => 'D:\\Documents\\server\\htdocs\\mysite\\РГР\\foto_deserts\\kruasan_migdal.jpg', 'destination' => __DIR__ . '/images/deserts/4.jpg'],
    ['source' => 'D:\\Documents\\server\\htdocs\\mysite\\РГР\\foto_deserts\\chocolate_fonda n.jpg', 'destination' => __DIR__ . '/images/deserts/5.jpg']
];

$kafeImagesPhotos = [
    ['source' => 'D:\\Documents\\server\\htdocs\\mysite\\PTP\\foto_kafe\\1.jpg', 'destination' => __DIR__ . '/images/kavarni/1.jpg'],
    ['source' => 'D:\\Documents\\server\\htdocs\\mysite\\PTP\\foto_kafe\\2.jpg', 'destination' => __DIR__ . '/images/kavarni/2.jpg'],
    ['source' => 'D:\\Documents\\server\\htdocs\\mysite\\PTP\\foto_kafe\\3.jpg', 'destination' => __DIR__ . '/images/kavarni/3.jpg'],
    ['source' => 'D:\\Documents\\server\\htdocs\\mysite\\PTP\\foto_kafe\\4.jpg', 'destination' => __DIR__ . '/images/kavarni/4.jpg'],
    ['source' => 'D:\\Documents\\server\\htdocs\\mysite\\PTP\\foto_kafe\\5.jpg', 'destination' => __DIR__ . '/images/kavarni/5.jpg']
];

// Выполняем копирование
echo "<h2>Копирование фотографий десертов:</h2>";
foreach ($desertPhotos as $photo) {
    copyFileWithCheck($photo['source'], $photo['destination']);
}

echo "<h2>Копирование фотографий кофеен:</h2>";
foreach ($kafePhotos as $photo) {
    copyFileWithCheck($photo['source'], $photo['destination']);
}

echo "<h2>Копирование фотографий десертов в папку images:</h2>";
foreach ($desertImagesPhotos as $photo) {
    copyFileWithCheck($photo['source'], $photo['destination']);
}

echo "<h2>Копирование фотографий кофеен в папку images:</h2>";
foreach ($kafeImagesPhotos as $photo) {
    copyFileWithCheck($photo['source'], $photo['destination']);
}

echo "<h2>Завершено!</h2>";
echo "<p>Теперь вы можете проверить работу фотографий на сайте.</p>";
echo "<p><a href='check_photos.php'>Перейти к проверке фотографий</a></p>";
?>
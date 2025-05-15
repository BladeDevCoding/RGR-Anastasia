<?php
// Проверка доступности стилей
$styles_path = __DIR__ . '/api/styles.css';
$public_styles_path = __DIR__ . '/public/api/styles.css';

echo "Проверка конфигурации Vercel:<br>";
echo "Styles path ($styles_path): " . (file_exists($styles_path) ? "Існує" : "Не існує") . "<br>";
echo "Public styles path ($public_styles_path): " . (file_exists($public_styles_path) ? "Існує" : "Не існує") . "<br>";

// Вывод информации о директориях
echo "<hr>Директории:<br>";
$dirs = ['api', 'public', 'public/api'];
foreach ($dirs as $dir) {
    $path = __DIR__ . '/' . $dir;
    echo "$dir: " . (is_dir($path) ? "Існує" : "Не існує") . "<br>";
    if (is_dir($path)) {
        echo "Файли в $dir:<br>";
        $files = scandir($path);
        echo "<pre>" . print_r($files, true) . "</pre>";
    }
} 
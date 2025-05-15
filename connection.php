<?php
// Шлях до директорії з JSON файлами
$json_dir = __DIR__ . '/data/';

// Переконуємося, що директорія існує
if (!file_exists($json_dir)) {
    mkdir($json_dir, 0755, true);
}

// Шляхи до файлів з даними
$files = [
    'kava' => $json_dir . 'kava.json',
    'ingredienty' => $json_dir . 'ingredienty.json',
    'kava_ingredienty' => $json_dir . 'kava_ingredienty.json',
    'retsepty' => $json_dir . 'retsepty.json',
    'deserty' => $json_dir . 'deserty.json',
    'kav_yarni' => $json_dir . 'kav_yarni.json',
    'kotyky' => $json_dir . 'kotyky.json',
    'aktsiyi' => $json_dir . 'aktsiyi.json'
];

// Створюємо пусті JSON файли, якщо вони не існують
foreach ($files as $file) {
    if (!file_exists($file)) {
        file_put_contents($file, json_encode([]));
    }
}
?>
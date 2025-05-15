<?php
require_once 'connection.php';

// Базові функції для роботи з JSON файлами

// Зчитування даних з JSON-файлу
function readJsonFile($file) {
    if (!file_exists($file)) {
        file_put_contents($file, json_encode([]));
        return [];
    }
    
    $content = file_get_contents($file);
    return json_decode($content, true) ?: [];
}

// Запис даних у JSON-файл
function writeJsonFile($file, $data) {
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    return true;
}

// Генерація нового ID
function generateId($data) {
    if (empty($data)) {
        return 1;
    }
    
    $maxId = 0;
    foreach ($data as $item) {
        if (isset($item['id']) && $item['id'] > $maxId) {
            $maxId = $item['id'];
        }
    }
    
    return $maxId + 1;
}

// ФУНКЦІЇ ДЛЯ РОБОТИ З КАВОЮ

// Отримати всі види кави
function getAllKava() {
    global $files;
    $data = readJsonFile($files['kava']);
    
    // Сортування за назвою
    usort($data, function($a, $b) {
        return strcmp($a['nazva'], $b['nazva']);
    });
    
    return $data;
}

// Отримати каву за ID
function getKavaById($id) {
    global $files;
    $data = readJsonFile($files['kava']);
    
    foreach ($data as $item) {
        if ($item['id'] == $id) {
            return $item;
        }
    }
    
    return null;
}

// Додати новий вид кави
function addKava($nazva, $opis, $tsina, $chas_prihotuvannya, $dostupna = 1) {
    global $files;
    $data = readJsonFile($files['kava']);
    
    $newId = generateId($data);
    $newKava = [
        'id' => $newId,
        'nazva' => $nazva,
        'opis' => $opis,
        'tsina' => $tsina,
        'chas_prihotuvannya' => $chas_prihotuvannya,
        'dostupna' => $dostupna
    ];
    
    $data[] = $newKava;
    
    if (writeJsonFile($files['kava'], $data)) {
        return $newId;
    }
    
    return false;
}

// Оновити інформацію про каву
function updateKava($id, $nazva, $opis, $tsina, $chas_prihotuvannya, $dostupna) {
    global $files;
    $data = readJsonFile($files['kava']);
    
    foreach ($data as $key => $item) {
        if ($item['id'] == $id) {
            $data[$key] = [
                'id' => $id,
                'nazva' => $nazva,
                'opis' => $opis,
                'tsina' => $tsina,
                'chas_prihotuvannya' => $chas_prihotuvannya,
                'dostupna' => $dostupna
            ];
            
            return writeJsonFile($files['kava'], $data);
        }
    }
    
    return false;
}

// Видалити каву
function deleteKava($id) {
    global $files;
    
    // Видаляємо пов'язані записи
    // Видаляємо з kava_ingredienty
    $kavaIngredienty = readJsonFile($files['kava_ingredienty']);
    $kavaIngredienty = array_filter($kavaIngredienty, function($item) use ($id) {
        return $item['kava_id'] != $id;
    });
    writeJsonFile($files['kava_ingredienty'], $kavaIngredienty);
    
    // Видаляємо з retsepty
    $retsepty = readJsonFile($files['retsepty']);
    $retsepty = array_filter($retsepty, function($item) use ($id) {
        return $item['kava_id'] != $id;
    });
    writeJsonFile($files['retsepty'], $retsepty);
    
    // Тепер видаляємо саму каву
    $data = readJsonFile($files['kava']);
    $newData = array_filter($data, function($item) use ($id) {
        return $item['id'] != $id;
    });
    
    return writeJsonFile($files['kava'], array_values($newData));
}

// ФУНКЦІЇ ДЛЯ РОБОТИ З ІНГРЕДІЄНТАМИ

// Отримати всі інгредієнти
function getAllIngredienty() {
    global $files;
    $data = readJsonFile($files['ingredienty']);
    
    // Сортування за назвою
    usort($data, function($a, $b) {
        return strcmp($a['nazva'], $b['nazva']);
    });
    
    return $data;
}

// Отримати інгредієнт за ID
function getIngredientById($id) {
    global $files;
    $data = readJsonFile($files['ingredienty']);
    
    foreach ($data as $item) {
        if ($item['id'] == $id) {
            return $item;
        }
    }
    
    return null;
}

// Додати новий інгредієнт
function addIngredient($nazva, $odynytsya) {
    global $files;
    $data = readJsonFile($files['ingredienty']);
    
    $newId = generateId($data);
    $newIngredient = [
        'id' => $newId,
        'nazva' => $nazva,
        'odynytsya' => $odynytsya
    ];
    
    $data[] = $newIngredient;
    
    if (writeJsonFile($files['ingredienty'], $data)) {
        return $newId;
    }
    
    return false;
}

// Оновити інформацію про інгредієнт
function updateIngredient($id, $nazva, $odynytsya) {
    global $files;
    $data = readJsonFile($files['ingredienty']);
    
    foreach ($data as $key => $item) {
        if ($item['id'] == $id) {
            $data[$key] = [
                'id' => $id,
                'nazva' => $nazva,
                'odynytsya' => $odynytsya
            ];
            
            return writeJsonFile($files['ingredienty'], $data);
        }
    }
    
    return false;
}

// Видалити інгредієнт
function deleteIngredient($id) {
    global $files;
    
    // Видаляємо пов'язані записи з kava_ingredienty
    $kavaIngredienty = readJsonFile($files['kava_ingredienty']);
    $kavaIngredienty = array_filter($kavaIngredienty, function($item) use ($id) {
        return $item['ingredient_id'] != $id;
    });
    writeJsonFile($files['kava_ingredienty'], $kavaIngredienty);
    
    // Тепер видаляємо сам інгредієнт
    $data = readJsonFile($files['ingredienty']);
    $newData = array_filter($data, function($item) use ($id) {
        return $item['id'] != $id;
    });
    
    return writeJsonFile($files['ingredienty'], array_values($newData));
}

// ФУНКЦІЇ ДЛЯ РОБОТИ З РЕЦЕПТАМИ

// Отримати всі кроки рецепту для конкретної кави
function getRetseptyByKavaId($kava_id) {
    global $files;
    $data = readJsonFile($files['retsepty']);
    
    $filteredData = array_filter($data, function($item) use ($kava_id) {
        return $item['kava_id'] == $kava_id;
    });
    
    // Сортування за кроком
    usort($filteredData, function($a, $b) {
        return $a['krok'] - $b['krok'];
    });
    
    return array_values($filteredData);
}

// Додати новий крок рецепту
function addRetsept($kava_id, $krok, $instruktsiya) {
    global $files;
    $data = readJsonFile($files['retsepty']);
    
    $newId = generateId($data);
    $newRetsept = [
        'id' => $newId,
        'kava_id' => $kava_id,
        'krok' => $krok,
        'instruktsiya' => $instruktsiya
    ];
    
    $data[] = $newRetsept;
    
    if (writeJsonFile($files['retsepty'], $data)) {
        return $newId;
    }
    
    return false;
}

// Оновити крок рецепту
function updateRetsept($id, $krok, $instruktsiya) {
    global $files;
    $data = readJsonFile($files['retsepty']);
    
    foreach ($data as $key => $item) {
        if ($item['id'] == $id) {
            $data[$key]['krok'] = $krok;
            $data[$key]['instruktsiya'] = $instruktsiya;
            
            return writeJsonFile($files['retsepty'], $data);
        }
    }
    
    return false;
}

// Видалити крок рецепту
function deleteRetsept($id) {
    global $files;
    $data = readJsonFile($files['retsepty']);
    
    $newData = array_filter($data, function($item) use ($id) {
        return $item['id'] != $id;
    });
    
    return writeJsonFile($files['retsepty'], array_values($newData));
}

// ФУНКЦІЇ ДЛЯ РОБОТИ З ІНГРЕДІЄНТАМИ КАВИ

// Отримати всі інгредієнти для конкретної кави
function getIngredientsForKava($kava_id) {
    global $files;
    $kavaIngredienty = readJsonFile($files['kava_ingredienty']);
    $ingredienty = readJsonFile($files['ingredienty']);
    
    $result = [];
    
    foreach ($kavaIngredienty as $ki) {
        if ($ki['kava_id'] == $kava_id) {
            foreach ($ingredienty as $i) {
                if ($i['id'] == $ki['ingredient_id']) {
                    $result[] = [
                        'kava_id' => $ki['kava_id'],
                        'ingredient_id' => $ki['ingredient_id'],
                        'kilkist' => $ki['kilkist'],
                        'nazva' => $i['nazva'],
                        'odynytsya' => $i['odynytsya']
                    ];
                    break;
                }
            }
        }
    }
    
    return $result;
}

// Додати інгредієнт до кави
function addIngredientToKava($kava_id, $ingredient_id, $kilkist) {
    global $files;
    $data = readJsonFile($files['kava_ingredienty']);
    
    // Перевіряємо, чи вже існує такий запис
    $exists = false;
    foreach ($data as $key => $item) {
        if ($item['kava_id'] == $kava_id && $item['ingredient_id'] == $ingredient_id) {
            $data[$key]['kilkist'] = $kilkist;
            $exists = true;
            break;
        }
    }
    
    if (!$exists) {
        $data[] = [
            'kava_id' => $kava_id,
            'ingredient_id' => $ingredient_id,
            'kilkist' => $kilkist
        ];
    }
    
    return writeJsonFile($files['kava_ingredienty'], $data);
}

// Видалити інгредієнт з кави
function removeIngredientFromKava($kava_id, $ingredient_id) {
    global $files;
    $data = readJsonFile($files['kava_ingredienty']);
    
    $newData = array_filter($data, function($item) use ($kava_id, $ingredient_id) {
        return !($item['kava_id'] == $kava_id && $item['ingredient_id'] == $ingredient_id);
    });
    
    return writeJsonFile($files['kava_ingredienty'], array_values($newData));
}

// ФУНКЦІЇ ДЛЯ РОБОТИ З ДЕСЕРТАМИ

// Отримати всі десерти
function getAllDeserty() {
    global $files;
    $data = readJsonFile($files['deserty']);
    
    // Сортування за назвою
    usort($data, function($a, $b) {
        return strcmp($a['nazva'], $b['nazva']);
    });
    
    return $data;
}

// Отримати десерт за ID
function getDesertById($id) {
    global $files;
    $data = readJsonFile($files['deserty']);
    
    foreach ($data as $item) {
        if ($item['id'] == $id) {
            return $item;
        }
    }
    
    return null;
}

// Додати новий десерт
function addDesert($nazva, $opis, $tsina, $vaha_gram, $dostupnyy = 1, $kategoria = null) {
    global $files;
    $data = readJsonFile($files['deserty']);
    
    $newId = generateId($data);
    $newDesert = [
        'id' => $newId,
        'nazva' => $nazva,
        'opis' => $opis,
        'tsina' => $tsina,
        'vaha_gram' => $vaha_gram,
        'dostupnyy' => $dostupnyy,
        'kategoria' => $kategoria
    ];
    
    $data[] = $newDesert;
    
    if (writeJsonFile($files['deserty'], $data)) {
        return $newId;
    }
    
    return false;
}

// Оновити інформацію про десерт
function updateDesert($id, $nazva, $opis, $tsina, $vaha_gram, $dostupnyy, $kategoria) {
    global $files;
    $data = readJsonFile($files['deserty']);
    
    foreach ($data as $key => $item) {
        if ($item['id'] == $id) {
            $data[$key] = [
                'id' => $id,
                'nazva' => $nazva,
                'opis' => $opis,
                'tsina' => $tsina,
                'vaha_gram' => $vaha_gram,
                'dostupnyy' => $dostupnyy,
                'kategoria' => $kategoria
            ];
            
            return writeJsonFile($files['deserty'], $data);
        }
    }
    
    return false;
}

// Видалити десерт
function deleteDesert($id) {
    global $files;
    $data = readJsonFile($files['deserty']);
    
    $newData = array_filter($data, function($item) use ($id) {
        return $item['id'] != $id;
    });
    
    return writeJsonFile($files['deserty'], array_values($newData));
}

// ФУНКЦІЇ ДЛЯ РОБОТИ З КАВ'ЯРНЯМИ

// Отримати всі кав'ярні
function getAllKavyarni() {
    global $files;
    $data = readJsonFile($files['kav_yarni']);
    
    // Сортування за назвою
    usort($data, function($a, $b) {
        return strcmp($a['nazva'], $b['nazva']);
    });
    
    return $data;
}

// Отримати кав'ярню за ID
function getKavyarnyaById($id) {
    global $files;
    $data = readJsonFile($files['kav_yarni']);
    
    foreach ($data as $item) {
        if ($item['id'] == $id) {
            return $item;
        }
    }
    
    return null;
}

// Додати нову кав'ярню
function addKavyarnya($nazva, $adresa, $telefon, $grafik_roboty, $opys) {
    global $files;
    $data = readJsonFile($files['kav_yarni']);
    
    $newId = generateId($data);
    $newKavyarnya = [
        'id' => $newId,
        'nazva' => $nazva,
        'adresa' => $adresa,
        'telefon' => $telefon,
        'grafik_roboty' => $grafik_roboty,
        'opys' => $opys
    ];
    
    $data[] = $newKavyarnya;
    
    if (writeJsonFile($files['kav_yarni'], $data)) {
        return $newId;
    }
    
    return false;
}

// Оновити інформацію про кав'ярню
function updateKavyarnya($id, $nazva, $adresa, $telefon, $grafik_roboty, $opys) {
    global $files;
    $data = readJsonFile($files['kav_yarni']);
    
    foreach ($data as $key => $item) {
        if ($item['id'] == $id) {
            $data[$key] = [
                'id' => $id,
                'nazva' => $nazva,
                'adresa' => $adresa,
                'telefon' => $telefon,
                'grafik_roboty' => $grafik_roboty,
                'opys' => $opys
            ];
            
            return writeJsonFile($files['kav_yarni'], $data);
        }
    }
    
    return false;
}

// Видалити кав'ярню
function deleteKavyarnya($id) {
    global $files;
    
    // Оновлюємо котиків, які живуть у цій кав'ярні
    $kotyky = readJsonFile($files['kotyky']);
    foreach ($kotyky as $key => $kotyk) {
        if ($kotyk['kav_yarnya_id'] == $id) {
            $kotyky[$key]['kav_yarnya_id'] = 1; // Переміщуємо котиків у першу кав'ярню
        }
    }
    writeJsonFile($files['kotyky'], $kotyky);
    
    // Тепер видаляємо саму кав'ярню
    $data = readJsonFile($files['kav_yarni']);
    $newData = array_filter($data, function($item) use ($id) {
        return $item['id'] != $id;
    });
    
    return writeJsonFile($files['kav_yarni'], array_values($newData));
}

// ФУНКЦІЇ ДЛЯ РОБОТИ З КОТИКАМИ

// Отримати всіх котиків
function getAllKotyky() {
    global $files;
    $data = readJsonFile($files['kotyky']);
    
    // Сортування за ім'ям
    usort($data, function($a, $b) {
        return strcmp($a['imya'], $b['imya']);
    });
    
    return $data;
}

// Отримати випадкових котиків
function getRandomKotyky($limit = 3) {
    global $files;
    $data = readJsonFile($files['kotyky']);
    
    // Перемішуємо масив
    shuffle($data);
    
    // Повертаємо обмежену кількість котиків
    return array_slice($data, 0, $limit);
}

// Отримати котика за ID
function getKotykById($id) {
    global $files;
    $data = readJsonFile($files['kotyky']);
    
    foreach ($data as $item) {
        if ($item['id'] == $id) {
            return $item;
        }
    }
    
    return null;
}

// Отримати котиків за ID кав'ярні
function getKotykyByKavyarnyaId($kav_yarnya_id) {
    global $files;
    $data = readJsonFile($files['kotyky']);
    
    $filteredData = array_filter($data, function($item) use ($kav_yarnya_id) {
        return $item['kav_yarnya_id'] == $kav_yarnya_id;
    });
    
    // Сортування за ім'ям
    usort($filteredData, function($a, $b) {
        return strcmp($a['imya'], $b['imya']);
    });
    
    return array_values($filteredData);
}

// Додати нового котика
function addKotyk($imya, $vik, $stat, $poroda, $harakterystyka, $kav_yarnya_id) {
    global $files;
    $data = readJsonFile($files['kotyky']);
    
    $newId = generateId($data);
    $newKotyk = [
        'id' => $newId,
        'imya' => $imya,
        'vik' => $vik,
        'stat' => $stat,
        'poroda' => $poroda,
        'harakterystyka' => $harakterystyka,
        'kav_yarnya_id' => $kav_yarnya_id
    ];
    
    $data[] = $newKotyk;
    
    if (writeJsonFile($files['kotyky'], $data)) {
        return $newId;
    }
    
    return false;
}

// Оновити інформацію про котика
function updateKotyk($id, $imya, $vik, $stat, $poroda, $harakterystyka, $kav_yarnya_id) {
    global $files;
    $data = readJsonFile($files['kotyky']);
    
    foreach ($data as $key => $item) {
        if ($item['id'] == $id) {
            $data[$key] = [
                'id' => $id,
                'imya' => $imya,
                'vik' => $vik,
                'stat' => $stat,
                'poroda' => $poroda,
                'harakterystyka' => $harakterystyka,
                'kav_yarnya_id' => $kav_yarnya_id
            ];
            
            return writeJsonFile($files['kotyky'], $data);
        }
    }
    
    return false;
}

// Видалити котика
function deleteKotyk($id) {
    global $files;
    $data = readJsonFile($files['kotyky']);
    
    $newData = array_filter($data, function($item) use ($id) {
        return $item['id'] != $id;
    });
    
    return writeJsonFile($files['kotyky'], array_values($newData));
}

// ФУНКЦІЇ ДЛЯ РОБОТИ З АКЦІЯМИ

// Отримати всі акції
function getAllAktsiyi() {
    global $files;
    $data = readJsonFile($files['aktsiyi']);
    
    // Сортування за датою закінчення (від нової до старої)
    usort($data, function($a, $b) {
        return strtotime($b['data_zakinchennya']) - strtotime($a['data_zakinchennya']);
    });
    
    return $data;
}

// Отримати активні акції
function getActiveAktsiyi() {
    global $files;
    $data = readJsonFile($files['aktsiyi']);
    $current_date = date('Y-m-d');
    
    // Фільтруємо активні акції
    $filteredData = array_filter($data, function($item) use ($current_date) {
        return $item['data_pochatku'] <= $current_date && $item['data_zakinchennya'] >= $current_date;
    });
    
    // Сортування за датою закінчення
    usort($filteredData, function($a, $b) {
        return strtotime($a['data_zakinchennya']) - strtotime($b['data_zakinchennya']);
    });
    
    return array_values($filteredData);
}

// Отримати акцію за ID
function getAktsiyaById($id) {
    global $files;
    $data = readJsonFile($files['aktsiyi']);
    
    foreach ($data as $item) {
        if ($item['id'] == $id) {
            return $item;
        }
    }
    
    return null;
}

// Додати нову акцію
function addAktsiya($nazva, $opis, $znyzhka, $data_pochatku, $data_zakinchennya, $kav_yarnya_id = null) {
    global $files;
    $data = readJsonFile($files['aktsiyi']);
    
    $newId = generateId($data);
    $newAktsiya = [
        'id' => $newId,
        'nazva' => $nazva,
        'opis' => $opis,
        'znyzhka' => $znyzhka,
        'data_pochatku' => $data_pochatku,
        'data_zakinchennya' => $data_zakinchennya,
        'kav_yarnya_id' => $kav_yarnya_id
    ];
    
    $data[] = $newAktsiya;
    
    if (writeJsonFile($files['aktsiyi'], $data)) {
        return $newId;
    }
    
    return false;
}

// Оновити інформацію про акцію
function updateAktsiya($id, $nazva, $opis, $znyzhka, $data_pochatku, $data_zakinchennya, $kav_yarnya_id) {
    global $files;
    $data = readJsonFile($files['aktsiyi']);
    
    foreach ($data as $key => $item) {
        if ($item['id'] == $id) {
            $data[$key] = [
                'id' => $id,
                'nazva' => $nazva,
                'opis' => $opis,
                'znyzhka' => $znyzhka,
                'data_pochatku' => $data_pochatku,
                'data_zakinchennya' => $data_zakinchennya,
                'kav_yarnya_id' => $kav_yarnya_id
            ];
            
            return writeJsonFile($files['aktsiyi'], $data);
        }
    }
    
    return false;
}

// Видалити акцію
function deleteAktsiya($id) {
    global $files;
    $data = readJsonFile($files['aktsiyi']);
    
    $newData = array_filter($data, function($item) use ($id) {
        return $item['id'] != $id;
    });
    
    return writeJsonFile($files['aktsiyi'], array_values($newData));
}
?>
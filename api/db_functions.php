<?php
require_once 'connection.php';

// Функція для підключення до бази даних
function connectDB() {
    global $host, $database, $user, $password;
    
    $conn = new mysqli($host, $user, $password, $database);
    
    if ($conn->connect_error) {
        die("Помилка підключення: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8");
    
    return $conn;
}

// ФУНКЦІЇ ДЛЯ РОБОТИ З КАВОЮ

// Отримати всі види кави
function getAllKava() {
    $conn = connectDB();
    $result = $conn->query("SELECT * FROM kava ORDER BY nazva");
    
    $kava = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $kava[] = $row;
        }
    }
    
    $conn->close();
    return $kava;
}

// Отримати каву за ID
function getKavaById($id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("SELECT * FROM kava WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $kava = $result->fetch_assoc();
    
    $stmt->close();
    $conn->close();
    
    return $kava;
}

// Додати новий вид кави
function addKava($nazva, $opis, $tsina, $chas_prihotuvannya, $dostupna = 1) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("INSERT INTO kava (nazva, opis, tsina, chas_prihotuvannya, dostupna) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdii", $nazva, $opis, $tsina, $chas_prihotuvannya, $dostupna);
    
    $result = $stmt->execute();
    $last_id = $conn->insert_id;
    
    $stmt->close();
    $conn->close();
    
    return $result ? $last_id : false;
}

// Оновити інформацію про каву
function updateKava($id, $nazva, $opis, $tsina, $chas_prihotuvannya, $dostupna) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("UPDATE kava SET nazva = ?, opis = ?, tsina = ?, chas_prihotuvannya = ?, dostupna = ? WHERE id = ?");
    $stmt->bind_param("ssdiis", $nazva, $opis, $tsina, $chas_prihotuvannya, $dostupna, $id);
    
    $result = $stmt->execute();
    
    $stmt->close();
    $conn->close();
    
    return $result;
}

// Видалити каву
function deleteKava($id) {
    $conn = connectDB();
    
    // Спочатку видаляємо пов'язані записи
    $stmt = $conn->prepare("DELETE FROM kava_ingredienty WHERE kava_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    
    $stmt = $conn->prepare("DELETE FROM retsepty WHERE kava_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    
    // Тепер видаляємо саму каву
    $stmt = $conn->prepare("DELETE FROM kava WHERE id = ?");
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    
    $stmt->close();
    $conn->close();
    
    return $result;
}

// ФУНКЦІЇ ДЛЯ РОБОТИ З ІНГРЕДІЄНТАМИ

// Отримати всі інгредієнти
function getAllIngredienty() {
    $conn = connectDB();
    $result = $conn->query("SELECT * FROM ingredienty ORDER BY nazva");
    
    $ingredienty = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $ingredienty[] = $row;
        }
    }
    
    $conn->close();
    return $ingredienty;
}

// Отримати інгредієнт за ID
function getIngredientById($id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("SELECT * FROM ingredienty WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $ingredient = $result->fetch_assoc();
    
    $stmt->close();
    $conn->close();
    
    return $ingredient;
}

// Оновити інформацію про інгредієнт
function updateIngredient($id, $nazva, $odynytsya) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("UPDATE ingredienty SET nazva = ?, odynytsya = ? WHERE id = ?");
    $stmt->bind_param("ssi", $nazva, $odynytsya, $id);
    
    $result = $stmt->execute();
    
    $stmt->close();
    $conn->close();
    
    return $result;
}

// Видалити інгредієнт
function deleteIngredient($id) {
    $conn = connectDB();
    
    // Спочатку видаляємо пов'язані записи
    $stmt = $conn->prepare("DELETE FROM kava_ingredienty WHERE ingredient_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    
    // Тепер видаляємо сам інгредієнт
    $stmt = $conn->prepare("DELETE FROM ingredienty WHERE id = ?");
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    
    $stmt->close();
    $conn->close();
    
    return $result;
}

// ФУНКЦІЇ ДЛЯ РОБОТИ З РЕЦЕПТАМИ

// Отримати всі кроки рецепту для конкретної кави
function getRetseptyByKavaId($kava_id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("SELECT * FROM retsepty WHERE kava_id = ? ORDER BY krok");
    $stmt->bind_param("i", $kava_id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    $retsepty = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $retsepty[] = $row;
        }
    }
    
    $stmt->close();
    $conn->close();
    
    return $retsepty;
}

// Додати новий крок рецепту
function addRetsept($kava_id, $krok, $instruktsiya) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("INSERT INTO retsepty (kava_id, krok, instruktsiya) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $kava_id, $krok, $instruktsiya);
    
    $result = $stmt->execute();
    $last_id = $conn->insert_id;
    
    $stmt->close();
    $conn->close();
    
    return $result ? $last_id : false;
}

// Оновити крок рецепту
function updateRetsept($id, $krok, $instruktsiya) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("UPDATE retsepty SET krok = ?, instruktsiya = ? WHERE id = ?");
    $stmt->bind_param("isi", $krok, $instruktsiya, $id);
    
    $result = $stmt->execute();
    
    $stmt->close();
    $conn->close();
    
    return $result;
}

// Видалити крок рецепту
function deleteRetsept($id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("DELETE FROM retsepty WHERE id = ?");
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    
    $stmt->close();
    $conn->close();
    
    return $result;
}

// ФУНКЦІЇ ДЛЯ РОБОТИ З ІНГРЕДІЄНТАМИ КАВИ

// Отримати всі інгредієнти для конкретної кави
function getIngredientsForKava($kava_id) {
    $conn = connectDB();
    
    $sql = "SELECT ki.kava_id, ki.ingredient_id, ki.kilkist, i.nazva, i.odynytsya 
            FROM kava_ingredienty ki 
            JOIN ingredienty i ON ki.ingredient_id = i.id 
            WHERE ki.kava_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $kava_id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    $ingredients = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $ingredients[] = $row;
        }
    }
    
    $stmt->close();
    $conn->close();
    
    return $ingredients;
}

// Додати інгредієнт до кави
function addIngredientToKava($kava_id, $ingredient_id, $kilkist) {
    $conn = connectDB();
    
    // Перевіряємо, чи вже існує такий запис
    $stmt = $conn->prepare("SELECT * FROM kava_ingredienty WHERE kava_id = ? AND ingredient_id = ?");
    $stmt->bind_param("ii", $kava_id, $ingredient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Якщо запис існує, оновлюємо кількість
        $stmt->close();
        $stmt = $conn->prepare("UPDATE kava_ingredienty SET kilkist = ? WHERE kava_id = ? AND ingredient_id = ?");
        $stmt->bind_param("dii", $kilkist, $kava_id, $ingredient_id);
    } else {
        // Якщо запису немає, створюємо новий
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO kava_ingredienty (kava_id, ingredient_id, kilkist) VALUES (?, ?, ?)");
        $stmt->bind_param("iid", $kava_id, $ingredient_id, $kilkist);
    }
    
    $result = $stmt->execute();
    
    $stmt->close();
    $conn->close();
    
    return $result;
}

// Видалити інгредієнт з кави
function removeIngredientFromKava($kava_id, $ingredient_id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("DELETE FROM kava_ingredienty WHERE kava_id = ? AND ingredient_id = ?");
    $stmt->bind_param("ii", $kava_id, $ingredient_id);
    $result = $stmt->execute();
    
    $stmt->close();
    $conn->close();
    
    return $result;
}

// ФУНКЦІЇ ДЛЯ РОБОТИ З ДЕСЕРТАМИ

// Отримати всі десерти
function getAllDeserty() {
    $conn = connectDB();
    $result = $conn->query("SELECT * FROM deserty ORDER BY nazva");
    
    $deserty = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $deserty[] = $row;
        }
    }
    
    $conn->close();
    return $deserty;
}

// Отримати десерт за ID
function getDesertById($id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("SELECT * FROM deserty WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $desert = $result->fetch_assoc();
    
    $stmt->close();
    $conn->close();
    
    return $desert;
}

// Додати новий десерт
function addDesert($nazva, $opis, $tsina, $vaha_gram, $dostupnyy = 1, $kategoria = null) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("INSERT INTO deserty (nazva, opis, tsina, vaha_gram, dostupnyy, kategoria) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdiis", $nazva, $opis, $tsina, $vaha_gram, $dostupnyy, $kategoria);
    
    $result = $stmt->execute();
    $last_id = $conn->insert_id;
    
    $stmt->close();
    $conn->close();
    
    return $result ? $last_id : false;
}

// Оновити інформацію про десерт
function updateDesert($id, $nazva, $opis, $tsina, $vaha_gram, $dostupnyy, $kategoria) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("UPDATE deserty SET nazva = ?, opis = ?, tsina = ?, vaha_gram = ?, dostupnyy = ?, kategoria = ? WHERE id = ?");
    $stmt->bind_param("ssdiisi", $nazva, $opis, $tsina, $vaha_gram, $dostupnyy, $kategoria, $id);
    
    $result = $stmt->execute();
    
    $stmt->close();
    $conn->close();
    
    return $result;
}

// Видалити десерт
function deleteDesert($id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("DELETE FROM deserty WHERE id = ?");
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    
    $stmt->close();
    $conn->close();
    
    return $result;
}

// ФУНКЦІЇ ДЛЯ РОБОТИ З КАВ'ЯРНЯМИ

// Отримати всі кав'ярні
function getAllKavyarni() {
    $conn = connectDB();
    $result = $conn->query("SELECT * FROM kav_yarni ORDER BY nazva");
    
    $kavyarni = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $kavyarni[] = $row;
        }
    }
    
    $conn->close();
    return $kavyarni;
}

// Отримати кав'ярню за ID
function getKavyarnyaById($id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("SELECT * FROM kav_yarni WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $kavyarnya = $result->fetch_assoc();
    
    $stmt->close();
    $conn->close();
    
    return $kavyarnya;
}

// Додати нову кав'ярню
function addKavyarnya($nazva, $adresa, $telefon, $grafik_roboty, $opys) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("INSERT INTO kav_yarni (nazva, adresa, telefon, grafik_roboty, opys) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nazva, $adresa, $telefon, $grafik_roboty, $opys);
    
    $result = $stmt->execute();
    $last_id = $conn->insert_id;
    
    $stmt->close();
    $conn->close();
    
    return $result ? $last_id : false;
}

// Оновити інформацію про кав'ярню
function updateKavyarnya($id, $nazva, $adresa, $telefon, $grafik_roboty, $opys) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("UPDATE kav_yarni SET nazva = ?, adresa = ?, telefon = ?, grafik_roboty = ?, opys = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $nazva, $adresa, $telefon, $grafik_roboty, $opys, $id);
    
    $result = $stmt->execute();
    
    $stmt->close();
    $conn->close();
    
    return $result;
}

// Видалити кав'ярню
function deleteKavyarnya($id) {
    $conn = connectDB();
    
    // Спочатку оновлюємо котиків, які живуть у цій кав'ярні
    $stmt = $conn->prepare("UPDATE kotyky SET kav_yarnya_id = 1 WHERE kav_yarnya_id = ?"); // Переміщуємо котиків у першу кав'ярню
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    
    // Тепер видаляємо саму кав'ярню
    $stmt = $conn->prepare("DELETE FROM kav_yarni WHERE id = ?");
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    
    $stmt->close();
    $conn->close();
    
    return $result;
}

// ФУНКЦІЇ ДЛЯ РОБОТИ З КОТИКАМИ

// Отримати всіх котиків
function getAllKotyky() {
    $conn = connectDB();
    $result = $conn->query("SELECT * FROM kotyky ORDER BY imya");
    
    $kotyky = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $kotyky[] = $row;
        }
    }
    
    $conn->close();
    return $kotyky;
}

// Отримати випадкових котиків
function getRandomKotyky($limit = 3) {
    $conn = connectDB();
    $result = $conn->query("SELECT * FROM kotyky ORDER BY RAND() LIMIT $limit");
    
    $kotyky = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $kotyky[] = $row;
        }
    }
    
    $conn->close();
    return $kotyky;
}

// Отримати котика за ID
function getKotykById($id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("SELECT * FROM kotyky WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $kotyk = $result->fetch_assoc();
    
    $stmt->close();
    $conn->close();
    
    return $kotyk;
}

// Отримати котиків за ID кав'ярні
function getKotykyByKavyarnyaId($kav_yarnya_id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("SELECT * FROM kotyky WHERE kav_yarnya_id = ? ORDER BY imya");
    $stmt->bind_param("i", $kav_yarnya_id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    $kotyky = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $kotyky[] = $row;
        }
    }
    
    $stmt->close();
    $conn->close();
    
    return $kotyky;
}

// Додати нового котика
function addKotyk($imya, $vik, $stat, $poroda, $harakterystyka, $kav_yarnya_id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("INSERT INTO kotyky (imya, vik, stat, poroda, harakterystyka, kav_yarnya_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisssi", $imya, $vik, $stat, $poroda, $harakterystyka, $kav_yarnya_id);
    
    $result = $stmt->execute();
    $last_id = $conn->insert_id;
    
    $stmt->close();
    $conn->close();
    
    return $result ? $last_id : false;
}

// Оновити інформацію про котика
function updateKotyk($id, $imya, $vik, $stat, $poroda, $harakterystyka, $kav_yarnya_id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("UPDATE kotyky SET imya = ?, vik = ?, stat = ?, poroda = ?, harakterystyka = ?, kav_yarnya_id = ? WHERE id = ?");
    $stmt->bind_param("sisssii", $imya, $vik, $stat, $poroda, $harakterystyka, $kav_yarnya_id, $id);
    
    $result = $stmt->execute();
    
    $stmt->close();
    $conn->close();
    
    return $result;
}

// Видалити котика
function deleteKotyk($id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("DELETE FROM kotyky WHERE id = ?");
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    
    $stmt->close();
    $conn->close();
    
    return $result;
}

// ФУНКЦІЇ ДЛЯ РОБОТИ З АКЦІЯМИ

// Отримати всі акції
function getAllAktsiyi() {
    $conn = connectDB();
    $result = $conn->query("SELECT * FROM aktsiyi ORDER BY data_zakinchennya DESC");
    
    $aktsiyi = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $aktsiyi[] = $row;
        }
    }
    
    $conn->close();
    return $aktsiyi;
}

// Отримати активні акції
function getActiveAktsiyi() {
    $conn = connectDB();
    $current_date = date('Y-m-d');
    
    $stmt = $conn->prepare("SELECT * FROM aktsiyi WHERE data_pochatku <= ? AND data_zakinchennya >= ? ORDER BY data_zakinchennya");
    $stmt->bind_param("ss", $current_date, $current_date);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    $aktsiyi = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $aktsiyi[] = $row;
        }
    }
    
    $stmt->close();
    $conn->close();
    
    return $aktsiyi;
}

// Отримати акцію за ID
function getAktsiyaById($id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("SELECT * FROM aktsiyi WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $aktsiya = $result->fetch_assoc();
    
    $stmt->close();
    $conn->close();
    
    return $aktsiya;
}

// Додати нову акцію
function addAktsiya($nazva, $opis, $znyzhka, $data_pochatku, $data_zakinchennya, $kav_yarnya_id = null) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("INSERT INTO aktsiyi (nazva, opis, znyzhka, data_pochatku, data_zakinchennya, kav_yarnya_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdssi", $nazva, $opis, $znyzhka, $data_pochatku, $data_zakinchennya, $kav_yarnya_id);
    
    $result = $stmt->execute();
    $last_id = $conn->insert_id;
    
    $stmt->close();
    $conn->close();
    
    return $result ? $last_id : false;
}

// Оновити інформацію про акцію
function updateAktsiya($id, $nazva, $opis, $znyzhka, $data_pochatku, $data_zakinchennya, $kav_yarnya_id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("UPDATE aktsiyi SET nazva = ?, opis = ?, znyzhka = ?, data_pochatku = ?, data_zakinchennya = ?, kav_yarnya_id = ? WHERE id = ?");
    $stmt->bind_param("ssdssii", $nazva, $opis, $znyzhka, $data_pochatku, $data_zakinchennya, $kav_yarnya_id, $id);
    
    $result = $stmt->execute();
    
    $stmt->close();
    $conn->close();
    
    return $result;
}

// Видалити акцію
function deleteAktsiya($id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("DELETE FROM aktsiyi WHERE id = ?");
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    
    $stmt->close();
    $conn->close();
    
    return $result;
}

// ФУНКЦІЇ ДЛЯ РОБОТИ З ІНГРЕДІЄНТАМИ

// Отримати всі інгредієнти
function getAllIngredients() {
    $conn = connectDB();
    $result = $conn->query("SELECT * FROM ingredienty ORDER BY nazva");
    
    $ingredienty = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $ingredienty[] = $row;
        }
    }
    
    $conn->close();
    return $ingredienty;
}

// Отримати інгредієнт за ID



?>
<?php
require_once 'connection.php';

// Функція для підключення до бази даних
function connectDB() {
    global $host, $database, $user, $password, $port, $sslmode;
    
    $dsn = "pgsql:host=$host;port=$port;dbname=$database;sslmode=$sslmode";
    
    try {
        $conn = new PDO($dsn, $user, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("Помилка підключення: " . $e->getMessage());
    }
}

// ФУНКЦІЇ ДЛЯ РОБОТИ З КАВОЮ

// Отримати всі види кави
function getAllKava() {
    $conn = connectDB();
    $stmt = $conn->query("SELECT * FROM kava ORDER BY nazva");
    
    $kava = [];
    if ($stmt) {
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $kava[] = $row;
        }
    }
    
    return $kava;
}

// Отримати каву за ID
function getKavaById($id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("SELECT * FROM kava WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    $kava = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $kava;
}

// Додати новий вид кави
function addKava($nazva, $opis, $tsina, $chas_prihotuvannya, $dostupna = 1) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("INSERT INTO kava (nazva, opis, tsina, chas_prihotuvannya, dostupna) VALUES (:nazva, :opis, :tsina, :chas_prihotuvannya, :dostupna) RETURNING id");
    $stmt->bindParam(':nazva', $nazva, PDO::PARAM_STR);
    $stmt->bindParam(':opis', $opis, PDO::PARAM_STR);
    $stmt->bindParam(':tsina', $tsina, PDO::PARAM_STR);
    $stmt->bindParam(':chas_prihotuvannya', $chas_prihotuvannya, PDO::PARAM_INT);
    $stmt->bindParam(':dostupna', $dostupna, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['id'];
    }
    
    return false;
}

// Оновити інформацію про каву
function updateKava($id, $nazva, $opis, $tsina, $chas_prihotuvannya, $dostupna) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("UPDATE kava SET nazva = :nazva, opis = :opis, tsina = :tsina, chas_prihotuvannya = :chas_prihotuvannya, dostupna = :dostupna WHERE id = :id");
    $stmt->bindParam(':nazva', $nazva, PDO::PARAM_STR);
    $stmt->bindParam(':opis', $opis, PDO::PARAM_STR);
    $stmt->bindParam(':tsina', $tsina, PDO::PARAM_STR);
    $stmt->bindParam(':chas_prihotuvannya', $chas_prihotuvannya, PDO::PARAM_INT);
    $stmt->bindParam(':dostupna', $dostupna, PDO::PARAM_INT);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    return $stmt->execute();
}

// Видалити каву
function deleteKava($id) {
    $conn = connectDB();
    
    // Спочатку видаляємо пов'язані записи
    $stmt = $conn->prepare("DELETE FROM kava_ingredienty WHERE kava_id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    $stmt = $conn->prepare("DELETE FROM retsepty WHERE kava_id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    // Тепер видаляємо саму каву
    $stmt = $conn->prepare("DELETE FROM kava WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    return $stmt->execute();
}

// ФУНКЦІЇ ДЛЯ РОБОТИ З ІНГРЕДІЄНТАМИ

// Отримати всі інгредієнти
function getAllIngredienty() {
    $conn = connectDB();
    $stmt = $conn->query("SELECT * FROM ingredienty ORDER BY nazva");
    
    $ingredienty = [];
    if ($stmt) {
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $ingredienty[] = $row;
        }
    }
    
    return $ingredienty;
}

// Отримати інгредієнт за ID
function getIngredientById($id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("SELECT * FROM ingredienty WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Оновити інформацію про інгредієнт
function updateIngredient($id, $nazva, $odynytsya) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("UPDATE ingredienty SET nazva = :nazva, odynytsya = :odynytsya WHERE id = :id");
    $stmt->bindParam(':nazva', $nazva, PDO::PARAM_STR);
    $stmt->bindParam(':odynytsya', $odynytsya, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    return $stmt->execute();
}

// Видалити інгредієнт
function deleteIngredient($id) {
    $conn = connectDB();
    
    // Спочатку видаляємо пов'язані записи
    $stmt = $conn->prepare("DELETE FROM kava_ingredienty WHERE ingredient_id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    // Тепер видаляємо сам інгредієнт
    $stmt = $conn->prepare("DELETE FROM ingredienty WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    return $stmt->execute();
}

// ФУНКЦІЇ ДЛЯ РОБОТИ З РЕЦЕПТАМИ

// Отримати всі кроки рецепту для конкретної кави
function getRetseptyByKavaId($kava_id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("SELECT * FROM retsepty WHERE kava_id = :kava_id ORDER BY krok");
    $stmt->bindParam(':kava_id', $kava_id, PDO::PARAM_INT);
    $stmt->execute();
    
    $retsepty = [];
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $retsepty[] = $row;
    }
    
    return $retsepty;
}

// Додати новий крок рецепту
function addRetsept($kava_id, $krok, $instruktsiya) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("INSERT INTO retsepty (kava_id, krok, instruktsiya) VALUES (:kava_id, :krok, :instruktsiya) RETURNING id");
    $stmt->bindParam(':kava_id', $kava_id, PDO::PARAM_INT);
    $stmt->bindParam(':krok', $krok, PDO::PARAM_INT);
    $stmt->bindParam(':instruktsiya', $instruktsiya, PDO::PARAM_STR);
    
    if ($stmt->execute()) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['id'];
    }
    
    return false;
}

// Оновити крок рецепту
function updateRetsept($id, $krok, $instruktsiya) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("UPDATE retsepty SET krok = :krok, instruktsiya = :instruktsiya WHERE id = :id");
    $stmt->bindParam(':krok', $krok, PDO::PARAM_INT);
    $stmt->bindParam(':instruktsiya', $instruktsiya, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    return $stmt->execute();
}

// Видалити крок рецепту
function deleteRetsept($id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("DELETE FROM retsepty WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    return $stmt->execute();
}

// ФУНКЦІЇ ДЛЯ РОБОТИ З ІНГРЕДІЄНТАМИ КАВИ

// Отримати всі інгредієнти для конкретної кави
function getIngredientsForKava($kava_id) {
    $conn = connectDB();
    
    $sql = "SELECT ki.kava_id, ki.ingredient_id, ki.kilkist, i.nazva, i.odynytsya 
            FROM kava_ingredienty ki 
            JOIN ingredienty i ON ki.ingredient_id = i.id 
            WHERE ki.kava_id = :kava_id";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':kava_id', $kava_id, PDO::PARAM_INT);
    $stmt->execute();
    
    $ingredients = [];
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $ingredients[] = $row;
    }
    
    return $ingredients;
}

// Додати інгредієнт до кави
function addIngredientToKava($kava_id, $ingredient_id, $kilkist) {
    $conn = connectDB();
    
    // Перевіряємо, чи вже існує такий запис
    $stmt = $conn->prepare("SELECT * FROM kava_ingredienty WHERE kava_id = :kava_id AND ingredient_id = :ingredient_id");
    $stmt->bindParam(':kava_id', $kava_id, PDO::PARAM_INT);
    $stmt->bindParam(':ingredient_id', $ingredient_id, PDO::PARAM_INT);
    $stmt->execute();
    
    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        // Якщо запис існує, оновлюємо кількість
        $stmt = $conn->prepare("UPDATE kava_ingredienty SET kilkist = :kilkist WHERE kava_id = :kava_id AND ingredient_id = :ingredient_id");
        $stmt->bindParam(':kilkist', $kilkist, PDO::PARAM_STR);
        $stmt->bindParam(':kava_id', $kava_id, PDO::PARAM_INT);
        $stmt->bindParam(':ingredient_id', $ingredient_id, PDO::PARAM_INT);
    } else {
        // Якщо запису немає, створюємо новий
        $stmt = $conn->prepare("INSERT INTO kava_ingredienty (kava_id, ingredient_id, kilkist) VALUES (:kava_id, :ingredient_id, :kilkist)");
        $stmt->bindParam(':kava_id', $kava_id, PDO::PARAM_INT);
        $stmt->bindParam(':ingredient_id', $ingredient_id, PDO::PARAM_INT);
        $stmt->bindParam(':kilkist', $kilkist, PDO::PARAM_STR);
    }
    
    return $stmt->execute();
}

// Видалити інгредієнт з кави
function removeIngredientFromKava($kava_id, $ingredient_id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("DELETE FROM kava_ingredienty WHERE kava_id = :kava_id AND ingredient_id = :ingredient_id");
    $stmt->bindParam(':kava_id', $kava_id, PDO::PARAM_INT);
    $stmt->bindParam(':ingredient_id', $ingredient_id, PDO::PARAM_INT);
    
    return $stmt->execute();
}

// ФУНКЦІЇ ДЛЯ РОБОТИ З ДЕСЕРТАМИ

// Отримати всі десерти
function getAllDeserty() {
    $conn = connectDB();
    $stmt = $conn->query("SELECT * FROM deserty ORDER BY nazva");
    
    $deserty = [];
    if ($stmt) {
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $deserty[] = $row;
        }
    }
    
    return $deserty;
}

// Отримати десерт за ID
function getDesertById($id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("SELECT * FROM deserty WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Додати новий десерт
function addDesert($nazva, $opis, $tsina, $vaha_gram, $dostupnyy = 1, $kategoria = null) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("INSERT INTO deserty (nazva, opis, tsina, vaha_gram, dostupnyy, kategoria) VALUES (:nazva, :opis, :tsina, :vaha_gram, :dostupnyy, :kategoria) RETURNING id");
    $stmt->bindParam(':nazva', $nazva, PDO::PARAM_STR);
    $stmt->bindParam(':opis', $opis, PDO::PARAM_STR);
    $stmt->bindParam(':tsina', $tsina, PDO::PARAM_STR);
    $stmt->bindParam(':vaha_gram', $vaha_gram, PDO::PARAM_INT);
    $stmt->bindParam(':dostupnyy', $dostupnyy, PDO::PARAM_INT);
    $stmt->bindParam(':kategoria', $kategoria, PDO::PARAM_STR);
    
    if ($stmt->execute()) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['id'];
    }
    
    return false;
}

// Оновити інформацію про десерт
function updateDesert($id, $nazva, $opis, $tsina, $vaha_gram, $dostupnyy, $kategoria) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("UPDATE deserty SET nazva = :nazva, opis = :opis, tsina = :tsina, vaha_gram = :vaha_gram, dostupnyy = :dostupnyy, kategoria = :kategoria WHERE id = :id");
    $stmt->bindParam(':nazva', $nazva, PDO::PARAM_STR);
    $stmt->bindParam(':opis', $opis, PDO::PARAM_STR);
    $stmt->bindParam(':tsina', $tsina, PDO::PARAM_STR);
    $stmt->bindParam(':vaha_gram', $vaha_gram, PDO::PARAM_INT);
    $stmt->bindParam(':dostupnyy', $dostupnyy, PDO::PARAM_INT);
    $stmt->bindParam(':kategoria', $kategoria, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    return $stmt->execute();
}

// Видалити десерт
function deleteDesert($id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("DELETE FROM deserty WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    return $stmt->execute();
}

// ФУНКЦІЇ ДЛЯ РОБОТИ З КАВ'ЯРНЯМИ

// Отримати всі кав'ярні
function getAllKavyarni() {
    $conn = connectDB();
    $stmt = $conn->query("SELECT * FROM kav_yarni ORDER BY nazva");
    
    $kavyarni = [];
    if ($stmt) {
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $kavyarni[] = $row;
        }
    }
    
    return $kavyarni;
}

// Отримати кав'ярню за ID
function getKavyarnyaById($id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("SELECT * FROM kav_yarni WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Додати нову кав'ярню
function addKavyarnya($nazva, $adresa, $telefon, $grafik_roboty, $opys) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("INSERT INTO kav_yarni (nazva, adresa, telefon, grafik_roboty, opys) VALUES (:nazva, :adresa, :telefon, :grafik_roboty, :opys) RETURNING id");
    $stmt->bindParam(':nazva', $nazva, PDO::PARAM_STR);
    $stmt->bindParam(':adresa', $adresa, PDO::PARAM_STR);
    $stmt->bindParam(':telefon', $telefon, PDO::PARAM_STR);
    $stmt->bindParam(':grafik_roboty', $grafik_roboty, PDO::PARAM_STR);
    $stmt->bindParam(':opys', $opys, PDO::PARAM_STR);
    
    if ($stmt->execute()) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['id'];
    }
    
    return false;
}

// Оновити інформацію про кав'ярню
function updateKavyarnya($id, $nazva, $adresa, $telefon, $grafik_roboty, $opys) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("UPDATE kav_yarni SET nazva = :nazva, adresa = :adresa, telefon = :telefon, grafik_roboty = :grafik_roboty, opys = :opys WHERE id = :id");
    $stmt->bindParam(':nazva', $nazva, PDO::PARAM_STR);
    $stmt->bindParam(':adresa', $adresa, PDO::PARAM_STR);
    $stmt->bindParam(':telefon', $telefon, PDO::PARAM_STR);
    $stmt->bindParam(':grafik_roboty', $grafik_roboty, PDO::PARAM_STR);
    $stmt->bindParam(':opys', $opys, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    return $stmt->execute();
}

// Видалити кав'ярню
function deleteKavyarnya($id) {
    $conn = connectDB();
    
    // Спочатку оновлюємо котиків, які живуть у цій кав'ярні
    $stmt = $conn->prepare("UPDATE kotyky SET kav_yarnya_id = 1 WHERE kav_yarnya_id = :id"); // Переміщуємо котиків у першу кав'ярню
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    // Тепер видаляємо саму кав'ярню
    $stmt = $conn->prepare("DELETE FROM kav_yarni WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    return $stmt->execute();
}

// ФУНКЦІЇ ДЛЯ РОБОТИ З КОТИКАМИ

// Отримати всіх котиків
function getAllKotyky() {
    $conn = connectDB();
    $stmt = $conn->query("SELECT * FROM kotyky ORDER BY imya");
    
    $kotyky = [];
    if ($stmt) {
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $kotyky[] = $row;
        }
    }
    
    return $kotyky;
}

// Отримати випадкових котиків
function getRandomKotyky($limit = 3) {
    $conn = connectDB();
    $stmt = $conn->query("SELECT * FROM kotyky ORDER BY RANDOM() LIMIT $limit");
    
    $kotyky = [];
    if ($stmt) {
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $kotyky[] = $row;
        }
    }
    
    return $kotyky;
}

// Отримати котика за ID
function getKotykById($id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("SELECT * FROM kotyky WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Отримати котиків за ID кав'ярні
function getKotykyByKavyarnyaId($kav_yarnya_id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("SELECT * FROM kotyky WHERE kav_yarnya_id = :kav_yarnya_id ORDER BY imya");
    $stmt->bindParam(':kav_yarnya_id', $kav_yarnya_id, PDO::PARAM_INT);
    $stmt->execute();
    
    $kotyky = [];
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $kotyky[] = $row;
    }
    
    return $kotyky;
}

// Додати нового котика
function addKotyk($imya, $vik, $stat, $poroda, $harakterystyka, $kav_yarnya_id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("INSERT INTO kotyky (imya, vik, stat, poroda, harakterystyka, kav_yarnya_id) VALUES (:imya, :vik, :stat, :poroda, :harakterystyka, :kav_yarnya_id) RETURNING id");
    $stmt->bindParam(':imya', $imya, PDO::PARAM_STR);
    $stmt->bindParam(':vik', $vik, PDO::PARAM_INT);
    $stmt->bindParam(':stat', $stat, PDO::PARAM_STR);
    $stmt->bindParam(':poroda', $poroda, PDO::PARAM_STR);
    $stmt->bindParam(':harakterystyka', $harakterystyka, PDO::PARAM_STR);
    $stmt->bindParam(':kav_yarnya_id', $kav_yarnya_id, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['id'];
    }
    
    return false;
}

// Оновити інформацію про котика
function updateKotyk($id, $imya, $vik, $stat, $poroda, $harakterystyka, $kav_yarnya_id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("UPDATE kotyky SET imya = :imya, vik = :vik, stat = :stat, poroda = :poroda, harakterystyka = :harakterystyka, kav_yarnya_id = :kav_yarnya_id WHERE id = :id");
    $stmt->bindParam(':imya', $imya, PDO::PARAM_STR);
    $stmt->bindParam(':vik', $vik, PDO::PARAM_INT);
    $stmt->bindParam(':stat', $stat, PDO::PARAM_STR);
    $stmt->bindParam(':poroda', $poroda, PDO::PARAM_STR);
    $stmt->bindParam(':harakterystyka', $harakterystyka, PDO::PARAM_STR);
    $stmt->bindParam(':kav_yarnya_id', $kav_yarnya_id, PDO::PARAM_INT);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    return $stmt->execute();
}

// Видалити котика
function deleteKotyk($id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("DELETE FROM kotyky WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    return $stmt->execute();
}

// ФУНКЦІЇ ДЛЯ РОБОТИ З АКЦІЯМИ

// Отримати всі акції
function getAllAktsiyi() {
    $conn = connectDB();
    $stmt = $conn->query("SELECT * FROM aktsiyi ORDER BY data_zakinchennya DESC");
    
    $aktsiyi = [];
    if ($stmt) {
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $aktsiyi[] = $row;
        }
    }
    
    return $aktsiyi;
}

// Отримати активні акції
function getActiveAktsiyi() {
    $conn = connectDB();
    $current_date = date('Y-m-d');
    
    $stmt = $conn->prepare("SELECT * FROM aktsiyi WHERE data_pochatku <= :current_date AND data_zakinchennya >= :current_date ORDER BY data_zakinchennya");
    $stmt->bindParam(':current_date', $current_date, PDO::PARAM_STR);
    $stmt->execute();
    
    $aktsiyi = [];
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $aktsiyi[] = $row;
    }
    
    return $aktsiyi;
}

// Отримати акцію за ID
function getAktsiyaById($id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("SELECT * FROM aktsiyi WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Додати нову акцію
function addAktsiya($nazva, $opis, $znyzhka, $data_pochatku, $data_zakinchennya, $kav_yarnya_id = null) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("INSERT INTO aktsiyi (nazva, opis, znyzhka, data_pochatku, data_zakinchennya, kav_yarnya_id) VALUES (:nazva, :opis, :znyzhka, :data_pochatku, :data_zakinchennya, :kav_yarnya_id) RETURNING id");
    $stmt->bindParam(':nazva', $nazva, PDO::PARAM_STR);
    $stmt->bindParam(':opis', $opis, PDO::PARAM_STR);
    $stmt->bindParam(':znyzhka', $znyzhka, PDO::PARAM_STR);
    $stmt->bindParam(':data_pochatku', $data_pochatku, PDO::PARAM_STR);
    $stmt->bindParam(':data_zakinchennya', $data_zakinchennya, PDO::PARAM_STR);
    $stmt->bindParam(':kav_yarnya_id', $kav_yarnya_id, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['id'];
    }
    
    return false;
}

// Оновити інформацію про акцію
function updateAktsiya($id, $nazva, $opis, $znyzhka, $data_pochatku, $data_zakinchennya, $kav_yarnya_id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("UPDATE aktsiyi SET nazva = :nazva, opis = :opis, znyzhka = :znyzhka, data_pochatku = :data_pochatku, data_zakinchennya = :data_zakinchennya, kav_yarnya_id = :kav_yarnya_id WHERE id = :id");
    $stmt->bindParam(':nazva', $nazva, PDO::PARAM_STR);
    $stmt->bindParam(':opis', $opis, PDO::PARAM_STR);
    $stmt->bindParam(':znyzhka', $znyzhka, PDO::PARAM_STR);
    $stmt->bindParam(':data_pochatku', $data_pochatku, PDO::PARAM_STR);
    $stmt->bindParam(':data_zakinchennya', $data_zakinchennya, PDO::PARAM_STR);
    $stmt->bindParam(':kav_yarnya_id', $kav_yarnya_id, PDO::PARAM_INT);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    return $stmt->execute();
}

// Видалити акцію
function deleteAktsiya($id) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("DELETE FROM aktsiyi WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    return $stmt->execute();
}

// ФУНКЦІЇ ДЛЯ РОБОТИ З ІНГРЕДІЄНТАМИ

// Отримати всі інгредієнти
function getAllIngredients() {
    $conn = connectDB();
    $stmt = $conn->query("SELECT * FROM ingredienty ORDER BY nazva");
    
    $ingredienty = [];
    if ($stmt) {
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $ingredienty[] = $row;
        }
    }
    
    return $ingredienty;
}

// Додати новий інгредієнт
function addIngredient($nazva, $odynytsya) {
    $conn = connectDB();
    
    $stmt = $conn->prepare("INSERT INTO ingredienty (nazva, odynytsya) VALUES (:nazva, :odynytsya) RETURNING id");
    $stmt->bindParam(':nazva', $nazva, PDO::PARAM_STR);
    $stmt->bindParam(':odynytsya', $odynytsya, PDO::PARAM_STR);
    
    if ($stmt->execute()) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['id'];
    }
    
    return false;
}

?>
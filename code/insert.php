<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db.php';

// Načti data z POST
$en_name = $_POST['en_name'] ?? null;
$jp_name = $_POST['jp_name'] ?? null;
$synopsis = $_POST['synopsis'] ?? null;
$id_type = isset($_POST['id_type']) ? (int)$_POST['id_type'] : 0;
$id_status = isset($_POST['id_status']) ? (int)$_POST['id_status'] : 0;
$episodes = isset($_POST['episodes']) ? (int)$_POST['episodes'] : 0;
$genres = $_POST['genres'] ?? [];
$series_name = !empty(trim($_POST['series_name'] ?? '')) ? trim($_POST['series_name']) : null;
$id_related_title = !empty($_POST['id_related_title']) ? (int)$_POST['id_related_title'] : null;
$id_relation_type = !empty($_POST['id_relation_type']) ? (int)$_POST['id_relation_type'] : null;

// Zpracování uploadu obrázku
$imageFileName = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['image']['tmp_name'];
    $fileName = $_FILES['image']['name'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    $allowedfileExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($fileExtension, $allowedfileExtensions)) {
        $uploadDir = __DIR__ . '/images/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $destPath = $uploadDir . $fileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            $imageFileName = $fileName;
        } else {
            die("Chyba při přesunu souboru.");
        }
    } else {
        die("Nepovolený formát souboru. Povolené: " . implode(", ", $allowedfileExtensions));
    }
} else {
    die("Obrázek nebyl nahrán správně.");
}

// Získání nebo vytvoření id_series
$id_series = null;
if ($series_name !== null) {
    $stmt = $mysqli->prepare("SELECT id_series FROM series WHERE series_name = ?");
    $stmt->bind_param("s", $series_name);
    $stmt->execute();
    $stmt->bind_result($id_series);
    if (!$stmt->fetch()) {
        $stmt->close();
        $stmt = $mysqli->prepare("INSERT INTO series (series_name) VALUES (?)");
        $stmt->bind_param("s", $series_name);
        $stmt->execute();
        $id_series = $stmt->insert_id;
    } else {
        $stmt->close();
    }
}

// Vložení do titles
$stmt = $mysqli->prepare("INSERT INTO titles (en_name, jp_name, synopsis, id_type, id_status, episodes, image, id_series) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssiiisi", $en_name, $jp_name, $synopsis, $id_type, $id_status, $episodes, $imageFileName, $id_series);
$stmt->execute();

$id_title = $stmt->insert_id;
$stmt->close();

// Vložení do title_genres
if (is_array($genres) && count($genres) > 0) {
    $stmt = $mysqli->prepare("INSERT INTO title_genres (id_title, id_genre) VALUES (?, ?)");
    foreach ($genres as $id_genre) {
        $id_genre = (int)$id_genre;
        $stmt->bind_param("ii", $id_title, $id_genre);
        $stmt->execute();
    }
    $stmt->close();
}

// Vložení do title_relations, pokud je nastaveno
if ($id_related_title && $id_relation_type) {
    $stmt = $mysqli->prepare("INSERT INTO title_relations (id_title, id_related_title, id_relation_type) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $id_title, $id_related_title, $id_relation_type);
    $stmt->execute();
    $stmt->close();
}

// Přesměrování
if (!headers_sent()) {
    header("Location: index.php");
    exit;
} else {
    echo "Hlavičky už byly odeslány, nelze přesměrovat.";
}

ob_end_flush();

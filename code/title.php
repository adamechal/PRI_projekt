<?php
require_once 'db.php';

function createSlug($string) {
    $slug = strtolower(trim($string));
    $slug = preg_replace('/[^a-z0-9-]+/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    return trim($slug, '-');
}

if (!isset($_GET['slug'])) {
    die("Neplatná stránka");
}

$slug = $_GET['slug'];

$query = "
    SELECT 
        t.*,
        ty.type_name,
        st.status_name,
        GROUP_CONCAT(g.genre_name SEPARATOR ', ') AS genres_list
    FROM titles t
    JOIN types ty ON t.id_type = ty.id_type
    JOIN statuses st ON t.id_status = st.id_status
    LEFT JOIN title_genres tg ON t.id_title = tg.id_title
    LEFT JOIN genres g ON tg.id_genre = g.id_genre
    GROUP BY t.id_title
";

$result = $mysqli->query($query);

$foundTitle = null;

while ($row = $result->fetch_assoc()) {
    if (createSlug($row['en_name']) === $slug) {
        $foundTitle = $row;
        break;
    }
}

if (!$foundTitle) {
    die("Titul nenalezen");
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo $foundTitle['en_name']; ?> - AniShelf</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body>

    <div id="logo">
        <a href="index.php"><img src="images/anishelf_logo.png" width="200" height="200" alt="AniShelf logo"></a>
    </div>

    <nav>
        <div class="nav-inner">
            <a href="index.php">Domů</a>
            <a href="anime.php">Anime</a>
            <a href="manga.php">Manga</a>
        </div>
    </nav>

    <div id="content" class="title-detail">

        <h1 class="title-en"><?php echo $foundTitle['en_name']; ?></h1>
        <h2 class="title-jp"><?php echo $foundTitle['jp_name']; ?></h2>

        <div class="title-main">

            <div class="title-image-info">
                <img src="images/<?php echo $foundTitle['image']; ?>" alt="<?php echo $foundTitle['en_name']; ?>" class="title-image">

                <div class="title-info">
                    <p><strong>Typ:</strong> <?php echo $foundTitle['type_name']; ?></p>
                    <p><strong>Status:</strong> <?php echo $foundTitle['status_name']; ?></p>
                    <p><strong>Žánry:</strong> <?php echo $foundTitle['genres_list'] ? $foundTitle['genres_list'] : 'Neznámé'; ?></p>
                    <p><strong>Epizody:</strong> <?php echo (int)$foundTitle['episodes']; ?></p>
                </div>
            </div>

            <div class="title-synopsis">
                <h3>Synopse</h3>
                <p><?php echo nl2br($foundTitle['synopsis']); ?></p>
            </div>

        </div>
         <br>
        <hr>

<?php 

$s = (int)$foundTitle['id_series'];
$currentId = (int)$foundTitle['id_title'];
$currentType = (int)$foundTitle['id_type'];

// 1. Získání všech titulů ze série kromě aktuálního
$result = $mysqli->query("
    SELECT t.*, ty.type_name
    FROM titles t
    JOIN types ty ON t.id_type = ty.id_type
    WHERE t.id_series = $s
      AND t.id_title != $currentId
    ORDER BY t.id_title
");

while ($row = $result->fetch_assoc()) {
    $relatedId = (int)$row['id_title'];
    $relationType = null;

    // 2. Zkontroluj vztah v title_relations
    $relQuery = $mysqli->query("
        SELECT rt.type_key, tr.id_title, tr.id_related_title
        FROM title_relations tr
        JOIN relation_types rt ON tr.id_relation_type = rt.id_relation_type
        WHERE (tr.id_title = $currentId AND tr.id_related_title = $relatedId)
           OR (tr.id_related_title = $currentId AND tr.id_title = $relatedId)
        LIMIT 1
    ");

    if ($rel = $relQuery->fetch_assoc()) {
        $key = $rel['type_key'];
        $isOutgoing = ($rel['id_title'] == $currentId);

        $map = [
            'sequel' => $isOutgoing ? 'Prequel' : 'Sequel',
            'prequel' => $isOutgoing ? 'Sequel' : 'Sequel',
            'remake' => 'Remake',
            'side_story' => $isOutgoing ? 'Vedlejší příběh' : 'Hlavní příběh',
        ];

        $relationType = $map[$key] ?? ucfirst($key);
    }

    // 3. Pokud nemá vztah, ale je jiného typu (manga/LN) → adaptace
    $typeLower = strtolower($row['type_name']);
    if (!$relationType && in_array($typeLower, ['manga', 'ln']) && $row['id_type'] != $currentType) {
        $relationType = 'Adaptace';
    }

    // 4. Titul se nezobrazí, pokud nesplňuje ani jedno
    if (!$relationType) {
        continue;
    }

    // 5. Vypisuj
    $slug = createSlug($row['en_name']);
    $isBook = in_array($typeLower, ['manga', 'ln']);
    $link = $isBook
        ? 'manga_ln_title.php?slug=' . urlencode($slug)
        : 'title.php?slug=' . urlencode($slug);

    echo '
    <a class="relation-card" href="' . $link . '">
        <div class="relation-text">
            <strong>' . htmlspecialchars($relationType) . '</strong> (' . htmlspecialchars($row['type_name']) . ')
        </div>
        <div class="relation-content">
            <img src="images/' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['en_name']) . '">
            <div>
                <div class="relation-title-en">' . htmlspecialchars($row['en_name']) . '</div>
                <div class="relation-title-jp">' . htmlspecialchars($row['jp_name']) . '</div>
            </div>
        </div>
    </a>';
}


?>
    </div>

</body>
</html>
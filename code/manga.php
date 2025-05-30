<?php
require_once 'db.php';

function createSlug($string) {
    $slug = strtolower(trim($string));
    $slug = preg_replace('/[^a-z0-9-]+/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    return trim($slug, '-');
}

$query = "
    SELECT 
        t.id_title,
        t.en_name,
        ty.type_name,
        t.image
    FROM titles t
    JOIN types ty ON t.id_type = ty.id_type
    WHERE ty.type_name IN ('manga', 'ln')
    ORDER BY t.id_series ASC, t.id_title ASC
";

$result = $mysqli->query($query);
$manga = [];

while ($row = $result->fetch_assoc()) {
    $row['slug'] = createSlug($row['en_name']);
    $manga[] = $row;
}

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>AniShelf - Manga</title>
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

    <div id="content">
        <h1>Manga a Light Novely</h1>

        <?php if(count($manga) === 0): ?>
            <p>Žádné manga tituly.</p>
        <?php else: ?>
            <?php foreach($manga as $row): ?>
                <div class="title-card">
                    <img src="images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['en_name']); ?>">
                    <div class="title-info">
                        <a href="title.php?slug=<?php echo urlencode($row['slug']); ?>"><?php echo htmlspecialchars($row['en_name']); ?></a><br>
                        <div class="details"><?php echo htmlspecialchars($row['type_name']); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</body>
</html>

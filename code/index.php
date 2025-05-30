<?php
require_once 'db.php';

function createSlug($string) {
    $slug = strtolower(trim($string));
    $slug = preg_replace('/[^a-z0-9-]+/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    return trim($slug, '-');
}

// Dotaz načte vše potřebné včetně typu
$query = "
   SELECT 
    t.id_title,
    t.en_name,
    t.episodes,
    ty.type_name,
    t.image,
    t.id_series
FROM titles t
JOIN types ty ON t.id_type = ty.id_type
ORDER BY t.id_series ASC, t.id_title ASC
";

$result = $mysqli->query($query);

$anime = [];
$manga = [];

while ($row = $result->fetch_assoc()) {
    $type = strtolower($row['type_name']);
    $row['slug'] = createSlug($row['en_name']); // přidám slug do řádku

    if ($type === 'tv' || $type === 'movie') {
        $anime[] = $row;
    } elseif ($type === 'manga' || $type === 'ln') {
        $manga[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>AniShelf - Seznam titulů</title>
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
        <h1>Seznam titulů</h1>

        <h2 class="section-title">Anime</h2>
        <?php if(count($anime) === 0): ?>
            <p>Žádné anime tituly.</p>
        <?php else: ?>
            <?php foreach($anime as $row): ?>
                <div class="title-card">
                    <img src="images/<?php echo $row['image']; ?>" alt="<?php echo $row['en_name']; ?>">
                    <div class="title-info">
                        <a href="title.php?slug=<?php echo $row['slug']; ?>"><?php echo $row['en_name']; ?></a><br>
                        <div class="details"><?php echo $row['type_name'] . " (" . (int)$row['episodes'] . " eps)"; ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <h2 class="section-title">Manga</h2>
        <?php if(count($manga) === 0): ?>
            <p>Žádné manga tituly.</p>
        <?php else: ?>
            <?php foreach($manga as $row): ?>
                <div class="title-card">
                    <img src="images/<?php echo $row['image']; ?>" alt="<?php echo $row['en_name']; ?>">
                    <div class="title-info">
                        <a href="manga_ln_title.php?slug=<?php echo $row['slug']; ?>"><?php echo $row['en_name']; ?></a><br>
                        <div class="details"><?php echo $row['type_name']; ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</body>
</html>

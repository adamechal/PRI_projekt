<?php
require_once 'db.php';

function createSlug($string) {
    $slug = strtolower(trim($string));
    $slug = preg_replace('/[^a-z0-9-]+/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    return trim($slug, '-');
}

if (!isset($_GET['slug'])) {
    header("HTTP/1.1 400 Bad Request");
    echo "Chybějící slug.";
    exit;
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
    if (createSlug($row['en_name']) === $slug && in_array(strtolower($row['type_name']), ['manga', 'ln'])) {
        $foundTitle = $row;
        break;
    }
}

if (!$foundTitle) {
    header("HTTP/1.1 404 Not Found");
    echo "Manga nebo LN verze titulu nebyla nalezena.";
    exit;
}

// === XML konstrukce ===
$dom = new DOMDocument('1.0', 'UTF-8');
$dom->formatOutput = true;

// XSL link
$pi = $dom->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="manga_ln_detail.xsl"');
$dom->appendChild($pi);

$root = $dom->createElement('title');
$dom->appendChild($root);

$root->appendChild($dom->createElement('id', $foundTitle['id_title']));
$root->appendChild($dom->createElement('name_en', $foundTitle['en_name']));
$root->appendChild($dom->createElement('name_jp', $foundTitle['jp_name']));
$root->appendChild($dom->createElement('type', $foundTitle['type_name']));
$root->appendChild($dom->createElement('status', $foundTitle['status_name']));
$root->appendChild($dom->createElement('genres', $foundTitle['genres_list'] ?? 'Neznámé'));
$root->appendChild($dom->createElement('episodes', (int)$foundTitle['episodes']));
$root->appendChild($dom->createElement('image', $foundTitle['image']));
$root->appendChild($dom->createElement('synopsis', $foundTitle['synopsis']));
$root->appendChild($dom->createElement('slug', createSlug($foundTitle['en_name'])));

$relationsNode = $dom->createElement('relations');
$root->appendChild($relationsNode);

$s = (int)$foundTitle['id_series'];
$currentId = (int)$foundTitle['id_title'];
$currentType = (int)$foundTitle['id_type'];

// 1. Vztahy/adaptace
$displayedIDs = [];

$res = $mysqli->query("
    SELECT t.*, ty.type_name
    FROM titles t
    JOIN types ty ON t.id_type = ty.id_type
    WHERE t.id_series = $s AND t.id_title != $currentId
    ORDER BY t.id_title
");

while ($row = $res->fetch_assoc()) {
    $relatedId = (int)$row['id_title'];
    $relationType = null;

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
            'sequel' => $isOutgoing ? 'Sequel' : 'Prequel',
            'prequel' => $isOutgoing ? 'Prequel' : 'Sequel',
            'remake' => 'Remake',
            'side_story' => $isOutgoing ? 'Vedlejší příběh' : 'Hlavní příběh',
        ];
        $relationType = $map[$key] ?? ucfirst($key);
    }

    $typeLower = strtolower($row['type_name']);
    if (!$relationType && !in_array($typeLower, ['manga', 'ln']) && $row['id_type'] != $currentType) {
        $relationType = 'Adaptation';
    }

    if (!$relationType) continue;

    $displayedIDs[] = $row['id_title'];

    $relNode = $dom->createElement('relation');
    $relNode->appendChild($dom->createElement('type', $relationType));
    $relNode->appendChild($dom->createElement('name_en', $row['en_name']));
    $relNode->appendChild($dom->createElement('name_jp', $row['jp_name']));
    $relNode->appendChild($dom->createElement('image', $row['image']));
    $relNode->appendChild($dom->createElement('content_type', $row['type_name']));
    $relNode->appendChild($dom->createElement('slug', createSlug($row['en_name'])));
    $relationsNode->appendChild($relNode);
}

// 2. Ostatní manga/LN ze série (bez vztahu)
$res = $mysqli->query("
    SELECT t.*, ty.type_name
    FROM titles t
    JOIN types ty ON t.id_type = ty.id_type
    WHERE t.id_series = $s
      AND t.id_title != $currentId
      AND LOWER(ty.type_name) IN ('manga', 'ln')
    ORDER BY t.id_title
");

while ($row = $res->fetch_assoc()) {
    if (in_array($row['id_title'], $displayedIDs)) continue;

    $relNode = $dom->createElement('relation');
    $relNode->appendChild($dom->createElement('type', 'Adaptace'));
    $relNode->appendChild($dom->createElement('name_en', $row['en_name']));
    $relNode->appendChild($dom->createElement('name_jp', $row['jp_name']));
    $relNode->appendChild($dom->createElement('image', $row['image']));
    $relNode->appendChild($dom->createElement('content_type', $row['type_name']));
    $relNode->appendChild($dom->createElement('slug', createSlug($row['en_name'])));
    $relationsNode->appendChild($relNode);
}

header('Content-Type: application/xml; charset=UTF-8');
echo $dom->saveXML();

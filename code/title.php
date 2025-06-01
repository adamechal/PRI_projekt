<?php
require_once 'db.php';

function slugify($string) {
    return trim(preg_replace('/-+/', '-', preg_replace('/[^a-z0-9-]+/', '-', strtolower(trim($string)))), '-');
}

if (empty($_GET['slug'])) {
    http_response_code(400);
    exit("Chybějící slug.");
}

$slug = $_GET['slug'];

$query = "
    SELECT 
        t.*, ty.type_name, st.status_name,
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
    if (slugify($row['en_name']) === $slug) {
        $foundTitle = $row;
        break;
    }
}

if (!$foundTitle) {
    http_response_code(404);
    exit("Titul nenalezen.");
}

$dom = new DOMDocument('1.0', 'UTF-8');
$dom->formatOutput = true;
$dom->appendChild($dom->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="title_detail.xsl"'));

$root = $dom->createElement('title');
$dom->appendChild($root);

foreach ([
    'id_title' => 'id',
    'en_name' => 'name_en',
    'jp_name' => 'name_jp',
    'type_name' => 'type',
    'status_name' => 'status',
    'genres_list' => 'genres',
    'episodes' => 'episodes',
    'image' => 'image',
    'synopsis' => 'synopsis'
] as $key => $xmlName) {
    $root->appendChild($dom->createElement($xmlName, $foundTitle[$key] ?? ''));
}

$root->appendChild($dom->createElement('slug', slugify($foundTitle['en_name'])));

$relationsNode = $dom->createElement('relations');

$seriesId = (int)$foundTitle['id_series'];
$currentId = (int)$foundTitle['id_title'];
$currentType = (int)$foundTitle['id_type'];

$relResult = $mysqli->query("
    SELECT t.*, ty.type_name
    FROM titles t
    JOIN types ty ON t.id_type = ty.id_type
    WHERE t.id_series = $seriesId AND t.id_title != $currentId
    ORDER BY t.id_title
");

while ($row = $relResult->fetch_assoc()) {
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
        $isOutgoing = ($rel['id_title'] == $currentId);
        $map = [
            'sequel' => $isOutgoing ? 'Prequel' : 'Sequel',
            'prequel' => $isOutgoing ? 'Sequel' : 'Prequel',
            'remake' => 'Remake',
            'side_story' => $isOutgoing ? 'Vedlejší příběh' : 'Hlavní příběh',
        ];
        $relationType = $map[$rel['type_key']] ?? ucfirst($rel['type_key']);
    }

    if (!$relationType && in_array(strtolower($row['type_name']), ['manga', 'ln']) && $row['id_type'] != $currentType) {
        $relationType = 'Adaptation';
    }

    if (!$relationType) continue;

    $relNode = $dom->createElement('relation');
    foreach ([
        'type' => $relationType,
        'name_en' => $row['en_name'],
        'name_jp' => $row['jp_name'],
        'image' => $row['image'],
        'content_type' => $row['type_name'],
        'slug' => slugify($row['en_name'])
    ] as $tag => $value) {
        $relNode->appendChild($dom->createElement($tag, $value));
    }

    $relationsNode->appendChild($relNode);
}

$root->appendChild($relationsNode);

header('Content-Type: application/xml; charset=UTF-8');
echo $dom->saveXML();

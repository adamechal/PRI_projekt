<?php
require 'db.php';

function slugify($text) {
    return trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($text)), '-');
}

$result = $mysqli->query("
    SELECT 
    t.en_name,
    t.episodes,
    ty.type_name,
    t.image
FROM titles t
JOIN types ty ON t.id_type = ty.id_type
LEFT JOIN series s ON t.id_series = s.id_series
ORDER BY s.series_name ASC, t.id_title

");

$dom = new DOMDocument('1.0', 'UTF-8');
$titles = $dom->appendChild($dom->createElement('titles'));

while ($row = $result->fetch_assoc()) {
    $type = strtolower($row['type_name']);
    $category = in_array($type, ['tv', 'movie']) ? 'anime' : (in_array($type, ['manga', 'ln']) ? 'manga' : null);
    if (!$category) continue;

    $title = $titles->appendChild($dom->createElement('title'));
    $title->setAttribute('category', $category);
    foreach (['name' => $row['en_name'],
     'episodes' => (int)$row['episodes'],
      'type' => $row['type_name'],
       'image' => $row['image'],
        'slug' => slugify($row['en_name'])] as $key => $val) {
        $title->appendChild($dom->createElement($key, $val));
    }
}

if (!$dom->schemaValidate('schema.xsd')) {
    exit("Neplatný XML dokument.");
}

$xsl = new DOMDocument();
$xsl->load('transform_index.xsl');

$proc = new XSLTProcessor();
$proc->importStylesheet($xsl);

header('Content-Type: text/html; charset=UTF-8');
echo $proc->transformToXML($dom);
?>

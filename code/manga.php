<?php
require 'db.php';

function slugify($text) {
    return trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($text)), '-');
}

$result = $mysqli->query("
    SELECT 
    t.id_title,
    t.en_name,
    t.episodes,
    ty.type_name,
    t.image
FROM titles t
JOIN types ty ON t.id_type = ty.id_type
LEFT JOIN series s ON t.id_series = s.id_series
WHERE ty.type_name IN ('manga', 'ln')
ORDER BY s.series_name ASC, t.id_title ASC

");

$dom = new DOMDocument('1.0', 'UTF-8');
$titles = $dom->appendChild($dom->createElement('titles'));

while ($row = $result->fetch_assoc()) {
    $title = $titles->appendChild($dom->createElement('title'));
    $title->setAttribute('category', 'manga');
    foreach (['name' => $row['en_name'],
            'episodes' => (int)$row['episodes'],
            'type' => $row['type_name'],
            'image' => $row['image'],
            'slug' => slugify($row['en_name'])] as $k => $v) {
        $title->appendChild($dom->createElement($k, $v));
    }
}

if (!$dom->schemaValidate('schema.xsd')) {
    exit("Neplatný XML dokument.");
}

$xsl = new DOMDocument();
$xsl->load('transform_manga.xsl');

$proc = new XSLTProcessor();
$proc->importStylesheet($xsl);

header('Content-Type: text/html; charset=UTF-8');
echo $proc->transformToXML($dom);
?>

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
        t.episodes,
        ty.type_name,
        t.image
    FROM titles t
    JOIN types ty ON t.id_type = ty.id_type
    ORDER BY t.id_series ASC, t.id_title ASC
";

$result = $mysqli->query($query);

$dom = new DOMDocument('1.0', 'UTF-8');
$dom->formatOutput = true;

$titlesElement = $dom->createElement('titles');

while ($row = $result->fetch_assoc()) {
    $type = strtolower($row['type_name']);
    $category = in_array($type, ['tv', 'movie']) ? 'anime' : (in_array($type, ['manga', 'ln']) ? 'manga' : 'other');

    if ($category === 'other') {
        continue; // přeskočíme neznámé typy
    }

    $title = $dom->createElement('title');
    $title->setAttribute('category', $category);

    $title->appendChild($dom->createElement('name', $row['en_name']));
    $title->appendChild($dom->createElement('episodes', (int)$row['episodes']));
    $title->appendChild($dom->createElement('type', $row['type_name']));
    $title->appendChild($dom->createElement('image', $row['image']));
    $title->appendChild($dom->createElement('slug', createSlug($row['en_name'])));

    $titlesElement->appendChild($title);
}

$dom->appendChild($titlesElement);

// Validace XML podle XSD
if (!$dom->schemaValidate('schema.xsd')) {
    header('Content-Type: text/plain; charset=UTF-8');
    die("Neplatný XML dokument podle XSD.");
}

// XSL transformace
$xsl = new DOMDocument();
$xsl->load('transform_index.xsl');

$proc = new XSLTProcessor();
$proc->importStylesheet($xsl);

// Výstup HTML
header('Content-Type: text/html; charset=UTF-8');
echo $proc->transformToXML($dom);
?>

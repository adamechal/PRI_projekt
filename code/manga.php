<?php
require_once 'db.php';

function createSlug($string) {
    $slug = strtolower(trim($string));
    $slug = preg_replace('/[^a-z0-9-]+/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    return trim($slug, '-');
}

// 1. Dotaz pro MANGA a LN
$query = "
    SELECT 
        t.id_title,
        t.en_name,
        ty.type_name,
        t.image,
        t.episodes
    FROM titles t
    JOIN types ty ON t.id_type = ty.id_type
    WHERE ty.type_name IN ('manga', 'ln')
    ORDER BY t.id_series ASC, t.id_title ASC
";

$result = $mysqli->query($query);

// 2. Vytvoření XML dokumentu
$dom = new DOMDocument('1.0', 'UTF-8');
$dom->formatOutput = true;
$root = $dom->createElement('titles');
$dom->appendChild($root);

while ($row = $result->fetch_assoc()) {
    $title = $dom->createElement('title');

    // ✅ POŘADÍ musí odpovídat přesně XSD definici
    $title->appendChild($dom->createElement('id', $row['id_title']));
    $title->appendChild($dom->createElement('name', $row['en_name']));
    $title->appendChild($dom->createElement('episodes', (int)($row['episodes'] ?? 0)));
    $title->appendChild($dom->createElement('type', $row['type_name']));
    $title->appendChild($dom->createElement('image', $row['image']));
    $title->appendChild($dom->createElement('slug', createSlug($row['en_name'])));

    $title->setAttribute('category', 'manga');
    $root->appendChild($title);
}

// 3. Validace proti XSD
if (!$dom->schemaValidate('schema.xsd')) {
    // Pozor: nesmí být žádný výstup před header()
    header("HTTP/1.1 500 Internal Server Error");
    echo "Neplatný XML dokument podle XSD.";
    exit;
}

// 4. Výstup podle parametru
$format = $_GET['format'] ?? 'html';

if ($format === 'xml') {
    header('Content-Type: application/xml; charset=UTF-8');
    echo $dom->saveXML();
} elseif ($format === 'html') {
    $xsl = new DOMDocument();
    $xsl->load('transform.xsl');

    $xslt = new XSLTProcessor();
    $xslt->importStylesheet($xsl);

    header('Content-Type: text/html; charset=UTF-8');
    echo $xslt->transformToXML($dom);
} else {
    echo "Neplatný formát. Použijte ?format=xml nebo ?format=html.";
}

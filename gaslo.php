<?php
function error($message) {
    header('Location /#' . urlencode($message));
    exit;
}

$url = null;
$motto = null;
if (isset($_POST['url']))
    $url = $_POST['url'];
if (isset($_POST['motto'])) {
    $motto = $_POST['motto'];
    if (count($motto) >= 256)
        error('Гасло завелике');
    if (strpos($motto, '<') !== false || strpos($motto, '>') !== false)
        error('Гасло містить недопустимі символи');
}

$html = new DOMDocument('1.0', 'utf-8');
$html->loadHTMLFile('gaslo.html');
$anchor = $html->createElement('a');
$anchor->setAttribute('href', $_POST['url']);
$motto = $html->createTextNode($_POST['motto']);
$anchor->appendChild($motto);
$item = $html->createElement('li');
$item->appendChild($anchor);
$list = $html->getElementById('list');
$list->appendChild($item);
$html->saveHTMLFile('gaslo.html');
header('Location: /');
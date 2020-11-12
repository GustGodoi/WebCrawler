<?php

$proxy = '10.1.21.254:3128';

$arrayConfig = array(
    'http' => array(
        'proxy' => $proxy,
        'request_fulluri' => true
    ),
    'https' => array(
        'proxy' => $proxy,
        'request_fulluri' => true
    )
);

$context = stream_context_create($arrayConfig);

$url = 'http://gutenberg.org/';
$html = file_get_contents($url, false, $context);

$dom = new DOMDocument();
libxml_use_internal_errors(true);

// Transforma o HTML em objeto
$dom->loadHTML($html);
libxml_clear_errors();

// Captura das Tags </p>
$tagsP = $dom->getElementsByTagName('div');
$arrayP = [];

//Array de parágrafos
$arrayParagrafos = [];

foreach ($tagsP as $div) {
    $class = $div->getAttribute('class');
    if ($class == 'page_content') {
        $divsInternas = $div->getElementsByTagName('div');
        foreach ($divsInternas as $divInterna) {
            $classeInterna = $divInterna->getAttribute('class');
            if ($classeInterna == 'box_announce') {
                $PsInternos = $divInterna->getElementsByTagName('p');
                foreach ($PsInternos as $PsInterno) {
                    $arrayP[] = $PsInterno->nodeValue;
                }
                break;
            }
        }

        break;
    }
}

echo '<pre>';
print_r($arrayP);
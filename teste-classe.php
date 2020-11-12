<?php

require './util/GutenbergCrawler.php';

$gut = new GutenbergCrawler();
$paragrafos = $gut->getParagrafos();

print_r($paragrafos);

?>
<?php

require './util/Rc.php';

$gut = new RcCrawler();
$titulos = $gut->getNoticias();

print_r($titulos);

?>
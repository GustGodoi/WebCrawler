<?php

require './util/Rc.php';

$gut = new RcCrawler();
$titulos = $gut->getNoticias();
$textos = $gut->getTextos();
$img = $gut->getImagens();
// print_r($titulos);
// print_r($textos);
// print_r($img);


?>
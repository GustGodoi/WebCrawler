<?php

require './util/Rc.php';

$gut = new RcCrawler();
$paragrafos = $gut->getNoticias();

print_r($paragrafos);

?>
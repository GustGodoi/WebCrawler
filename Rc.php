<?php

class RcCrawler {

    private $url;
    private $proxy;
    private $dom;
    private $html;

    public function __construct() {
        //Seta os calores das variáveis
        $this->url = "https://rc.am.br/homes/page_noticias/editoria_6/";
        $this->proxy = "10.1.21.254:3128";
        $this->dom = new DOMDocument;
        
    }

    public function getNoticias() {
        $this->carregarHtml();
        $tagsDiv = $this->capturarTagsDivGeral();
        $divsInternas = $this->capturarDivsInternasPageContent($tagsDiv);
        $divNoticias = $this->capturarNoticias($divsInternas);
        $paragrafos = $this->getArrayParagrafos($divNoticias);
        $sadfg = $this->capturarTitulo($arrayTitulos);
        return $paragrafos;
    }

    private function getContextoConexao() {

        $arrayConfig = array(
            'http' => array(
                'proxy' => $this->proxy,
                'request_fulluri' => true
            ),
            'https' => array(
                'proxy' => $this->proxy,
                'request_fulluri' => true
            )
        );
        
        $context = stream_context_create($arrayConfig);
        return $context;

    }

    private function carregarHtml() {

        $context = $this->getContextoConexao();
        $this->html = file_get_contents($this->url, false, $context);

        libxml_use_internal_errors(true);

        // Transforma o HTML em objeto
        $this->dom->loadHTML($this->html);
        libxml_clear_errors();
    }

    Private function capturarTagsDivGeral() {

        $tagsDiv = $this->dom->getElementsByTagName('div');
        return $tagsDiv;

    }

    Private function capturarDivsInternasPageContent($divsGeral) {

        $divsInternas = null;

        foreach ($divsGeral as $div) {
            $class = $div->getAttribute('class');

            if ($class == 'col s12 m12 l12') {
                $divsInternas = $div->getElementsByTagName('a');
                break;
            }
        }

        return $divsInternas;
    }

    private function capturarNoticias($divsInternas) {

        $divNoticias = null;

        foreach ($divsInternas as $divNoticias) {
            $classeInterna = $divNoticias->getAttribute('class');
            if (strlen($divNoticias->nodeValue) > 20) {
                $arrayP [] = $divNoticias;
            }
        }
        return $arrayP;
    }

    private function getArrayParagrafos($divNoticias) {
        
        $arrayP = [];
        foreach ($divNoticias as $divNoticia) {
            $arrayP [] = $divNoticia->nodeValue;
        }
        return $arrayP;
    }

    private function capturarTitulo($arrayP) {
        
        $arrayTitulos = [];
        foreach ($arrayP as $divNoticia) {
            $h4 = $divNoticia->getElementsByTagName('h4');
            foreach ($h4 as $Titulo) {
                $arrayTitulos = $Titulo->nodeValue;
            }
        }
        return $arrayTitulos;
    }
}
?>
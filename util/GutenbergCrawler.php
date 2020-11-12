<?php

class GutenbergCrawler {

    private $url;
    private $proxy;
    private $dom;
    private $html;

    public function __construct() {
        //Seta os calores das variáveis
        $this->url = "http://gutenberg.org/";
        $this->proxy = "10.1.21.254:3128";
        $this->dom = new DOMDocument;
        
    }

    public function getParagrafos() {
        $this->carregarHtml();
        $tagsDiv = $this->capturarTagsDivGeral();
        $divsInternas = $this->capturarDivsInternasPageContent($tagsDiv);
        $tagsP = $this->capturartagsP($divsInternas);
        $paragrafos = $this->getArrayParagrafos($tagsP);
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

            if ($class == 'page_content') {
                $divsInternas = $div->getElementsByTagName('div');
                break;
            }
        }

        return $divsInternas;
    }

    private function capturartagsP($divsInternas) {

        $PsInternos = null;

        foreach ($divsInternas as $divInterna) {
            $classeInterna = $divInterna->getAttribute('class');
            if ($classeInterna == 'box_announce') {
                $PsInternos = $divInterna->getElementsByTagName('p');
            }
        }
        return $PsInternos;
    }

    private function getArrayParagrafos($PsInternos) {
        
        $arrayP = [];
        foreach ($PsInternos as $PsInterno) {
            $arrayP [] = $PsInterno->nodeValue;
        }
        return $arrayP;
    }


}
?>
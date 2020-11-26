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
        $titulos = $this->capturarTitulo($paragrafos);
        return $titulos;
    }

    public function getTextos() {
        $this->carregarHtml();
        $tagsDiv = $this->capturarTagsDivGeral();
        $divsInternas = $this->capturarDivsInternasPageContent($tagsDiv);
        $divNoticias = $this->capturarNoticias($divsInternas);
        $paragrafos = $this->getArrayTextos($divNoticias);
        $textos = $this->capturarTextos($paragrafos);
        return $textos;
    }
    
    public function getImagens() {
        $this->carregarHtml();
        $tagsDiv = $this->capturarTagsDivGeral();
        $divsInternas = $this->capturarDivsInternasPageContent($tagsDiv);
        $divNoticias = $this->capturarNoticias($divsInternas);
        $paragrafos = $this->getArrayImagens($divNoticias);
        $img = $this->capturarImagens($paragrafos);
        return $img;
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

        $divsInternas = [];

        foreach ($divsGeral as $div) {
            $class = $div->getAttribute('class');
            if ($class == 'col s12 m12 l12') {
                $divsInternas[] = $div->getElementsByTagName('a');
            }
        }

        return $divsInternas;
    }

    private function capturarNoticias($divsInternas) {

        $divNoticias = null;
        $arrayP = [];
        
        foreach ($divsInternas as $divNoticias) {
            foreach ($divNoticias as $div) {
                $classeInterna = $div->getAttribute('class');
                if (strlen($div->nodeValue) > 20) {
                    $arrayP[] = $div->getElementsByTagName('div');
                }
            }
        }
        return $arrayP;
    }

    private function getArrayParagrafos($divNoticias) {
        
        $arrayP = [];
        foreach ($divNoticias as $divNoticia) {
            foreach ($divNoticia as $divs) {
                $class = $divs->getAttribute('class');

                if ($class == 'col s12 ') {
                    $titulo = $divs->getElementsByTagName('h4');
                    $arrayP [] = $titulo;
                }
            }
        }
        return $arrayP;
    }

    private function capturarTitulo($arrayP) {
        
        $arrayTitulos = [];
        foreach ($arrayP as $divNoticia) {
            foreach ($divNoticia as $titulo) {
                
                $arrayTitulos[] = $titulo->nodeValue;
            }
        }
        return $arrayTitulos;
    }

///////////////////////////////////////////////////////////////////////////

    private function getArrayTextos($divNoticias) {
        
        $arrayTextos = [];
        foreach ($divNoticias as $divNoticia) {
            foreach ($divNoticia as $divTextos) {
                $class = $divTextos->getAttribute('class');

                if ($class == 'col s12') {
                    $Texto = $divTextos->getElementsByTagName('div');
                    $arrayTextos[] = $Texto;
                }
            }
        }
        return $arrayTextos;
    }

    private function capturarTextos($arrayTextos) {
        
        $arrayTexto = [];
        foreach ($arrayTextos as $divNoticia) {
            foreach ($divNoticia as $txt) {
                
                $arrayTexto[] = $txt->nodeValue;
            }
        }
        return $arrayTexto;
    }
///////////////////////////////////////////////////////////////////////////

    private function getArrayImagens($divNoticias) {
        
        $arrayImagens = [];
        foreach ($divNoticias as $divNoticia) {
            foreach ($divNoticia as $imagem) {
                $class = $imagem->getAttribute('class');
                if ($class == 'col s12 img-list scale') {
                    $imagens = $imagem->getAttribute('style');
                    $imagens = str_replace("background-image:url('", "", $imagens);
                    $imagens = str_replace("'); margin-bottom: 12px;", "", $imagens);
                    $arrayImagens[] = $imagens;

                }
            }
        }
        return $arrayImagens;
    }

    private function capturarImagens($arrayImagens) {
        
        $arrayImagem = [];
        foreach ($arrayImagens as $divNoticia) {
            foreach ($divNoticia as $imgs) {
                
                $arrayImagem[] = $imgs->nodeValue;
            }
        }
        var_dump($arrayImagem);
        return $arrayImagem;
    }
}
?>
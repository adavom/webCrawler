<?php

namespace adm\SpiderForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DomCrawler\Crawler;
use Goutte\Client;

class DefaultController extends Controller {

    private $uri;
    private $method;
    
    private $metas = 'head meta[name="generator"]';
    private $values_search = '';

    function __construct($uri, $method) {
        $this->uri = $uri;
        $this->method = $method;
    }

    /**
     * Funcion con la que realizaremos la deteccion de tipo de foro que nos estan solicitando
     * @param type $name
     * @return type
     */
    public function detect() {
        $client = new Client();
        $crawler = $client->request($this->method, $this->uri);
        $client->getResponse()->getStatus();
        
        // Filtramos por metas
        $crawler->filter($metas)->each(function(Crawler $node, $i){
            
        });
    }

    /**
     * Funcion con la que extraeremos el arbol de foros a partir de la url
     * @param type $name
     * @return type
     */
    public function crawlForums() {
        
    }

    /**
     * Funcion con la que extraeremos los Temas del foro indicado
     * @param type $name
     * @return type
     */
    public function crawlTopics() {
        
    }

}

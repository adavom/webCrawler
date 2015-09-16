<?php

namespace Util\CrawlerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class DefaultController extends Controller {

    private $uri;
    private $selectorForum = '.forabg, .panel.panel-forum';
    private $selectorForumTitle = 'a.forumtitle, .panel-heading a';
    private $selectorForumTime = '.forum-time';
    private $selectorForumSubforum = '.subforums a';

    /**
     * @Route("/crawler/{uri}")
     * @Template()
     */
    public function crawlerAction($uri) {
        // Asignamos la URL a recorrer
        $this->uri = 'http://www.phpbb-es.com/foro/';

        // Comprobamos que esta haya sido informada y sea correcta
        if (!$this->uri)
            throw $this->createNotFoundException('No se ha informado url');

        // Iniciamos el cliente y solicitamos el HTML
        $client = new Client();
        $crawler = $client->request("GET", $this->uri);

        // Comprobamos si hay foros que recorrer
        if ($crawler->filter($this->selectorForum)->count() === 0)
            throw $this->createNotFoundException('No se han encontrado foros que seguir dentro de ' . $this->uri);

        // Recorremos los foros para obtener la informacion
        $nodes = array();
        $crawler->filter($this->selectorForum)->each(
                function(Crawler $node, $i) use (&$nodes) {

            if ($node->filter($this->selectorForumSubforum)->count() > 0) {
                $subNodes = $node->filter($this->selectorForumSubforum)->each(function(Crawler $node, $i) {
                    return array('title' => $node->text(),
                        'url' => $node->link()->getUri());
                });
            } else {
                $subNodes = null;
            }

            $nodes[] = array(
                'title' => $node->filter($this->selectorForumTitle)->text(),
                'url' => $node->filter($this->selectorForumTitle)->link()->getUri(),
                'lastMessage' => $node->filter($this->selectorForumTime)->text(),
                'subForosCount' => $node->filter($this->selectorForumSubforum)->count(),
                'subForos' => $subNodes
            );
        }
        );






        //$nodesvalues = $crawler->filter(/*.forabg,*/'.panel-forum')->each(function(Crawler $node, $i){

        /* $subnode = $node->filter('.row')->each(function(Crawler $node, $i){
          return array(
          'titulo' => $node->filter('a.forumtitle')->text(),
          'uri' => $node->filter('a.forumtitle')->link()->getUri()
          );
          }); */


        /* return array(
          'crawler' => $total//$crawler->html()
          //'titulo' => $node->filter('.header > .icon > .dterm > .table-title > h2')->text(),
          //'subnodos' => $subnode
          ); */
        //});

        return array('resultado' => json_encode($nodes));
    }

}

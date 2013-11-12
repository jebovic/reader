<?php

namespace Reader\Bundle\GrabberBundle\Service;

use Symfony\Component\DomCrawler\Crawler;
use Goutte\Client;

class GrabberClient extends Client
{
    /**
     * Creates a crawler.
     *
     * This method returns null if the DomCrawler component is not available.
     *
     * @param string $uri     A uri
     * @param string $content Content for the crawler to use
     * @param string $type    Content type
     *
     * @return Crawler|null
     */
    protected function createCrawlerFromContent($uri, $content, $type)
    {
        if (!class_exists('Symfony\Component\DomCrawler\Crawler')) {
            return null;
        }

        $crawler = new Crawler(null, $uri);
        if ( strpos( $type, 'charset' ) == null )
        {
            $type = $type.';charset=UTF-8';
        }
        $crawler->addContent($content, $type);

        return $crawler;
    }
}
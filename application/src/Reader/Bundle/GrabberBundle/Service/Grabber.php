<?php

namespace Reader\Bundle\GrabberBundle\Service;

use Reader\Bundle\ReaderBundle\Document\Site;
use Symfony\Component\DomCrawler\Crawler;
use Goutte\Client;

class Grabber
{
    /**
     * @var \Reader\Bundle\ReaderBundle\Document\Site;
     */
    protected $site;
    protected $page;

    public function init(Site $site, $page)
    {
        $this->site = $site;
        $this->page = $page;

        return $this;
    }

    public function grab()
    {
        $client  = new Client();
        $url     = $this->constructUrl();
        $crawler = $client->request('GET', $url);
        $stories = $this->getStories( $crawler );
        return $stories;
    }

    protected function constructUrl()
    {
        $pageNumber = (int) $this->site->getUrlStep() * ( $this->page - 1 + (int) $this->site->getUrlFirstPage() );
        $url        = $this->site->getUrl() . sprintf( $this->site->getUrlPattern(), $pageNumber );

        return $url;
    }

    protected function getStories(Crawler $crawler)
    {
        $allowedTags = $this->site->getAllowedTags();
        $selector    = $this->site->getGrabSelector();
        $nodes       = $crawler->filter( $selector )->each(function ($node, $i) use( $allowedTags )
        {
            $content = $node->html();
            return strip_tags($content, $allowedTags);
        });

        return $nodes;
    }
}

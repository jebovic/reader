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
        $content     = array( 'html' => '', 'image' => null);
        $nodes       = $crawler->filter( $selector )->each(function ($node, $i) use( $allowedTags, $content )
        {
            if ( $imageSelector = $this->site->getImageTag() )
            {
                if ( strpos( $imageSelector, 'parent' ) === 0 )
                {
                    $imageSelector = trim( str_replace( 'parent', '', $imageSelector ) );
                    try {
                        $imageUrl = $node->siblings()->filter( $imageSelector )->first()->attr('src');
                    } catch ( \Exception $e)
                    {
                        return false;
                    }
                }
                else
                {
                    try {
                        $imageUrl = $node->filter( $imageSelector )->first()->attr('src');
                    } catch ( \Exception $e)
                    {
                        return false;
                    }
                }
                $content['image'] = $imageUrl;
            }
            $content['html'] = strip_tags($node->html(), $allowedTags);
            return $content;
        });

        return $nodes;
    }
}

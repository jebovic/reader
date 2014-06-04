<?php

namespace Reader\Bundle\GrabberBundle\Service;

use Reader\Bundle\ReaderBundle\Document\Site;
use Symfony\Component\DomCrawler\Crawler;
use Reader\Bundle\GrabberBundle\Service\GrabberClient;


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
        $client  = new GrabberClient();
        $url     = $this->constructUrl();
        $crawler = $client
            ->setHeader('Content-Type', 'text/html; charset=utf-8')
            ->request('GET', $url);
        if ( $this->site->getDetails() )
        {
            $stories = $this->getFullStories( $crawler );
        }
        else {
            $stories = $this->getStories( $crawler );
        }
        return $stories;
    }

    protected function constructUrl()
    {
        $pageNumber = (int) $this->site->getUrlStep() * ( $this->page - 1 + (int) $this->site->getUrlFirstPage() );
        $url        = $this->site->getUrl() . sprintf( $this->site->getUrlPattern(), $pageNumber );

        return $url;
    }

    protected function getFullStories(Crawler $crawler)
    {
        $listItemSelector = $this->site->getListItemSelector();
        $detailsLinkSelector = $this->site->getDetailsLinkSelector();
        $nodes           = $crawler->filter( $listItemSelector . ' ' . $detailsLinkSelector )->each(function ($node, $i) use( $detailsLinkSelector )
        {
            return $node->attr('href');
        });
        $stories = array();
        foreach ( $nodes as $detailsUrl )
        {
            $client  = new GrabberClient();
            $detailCrawler = $client
                ->setHeader('Content-Type', 'text/html; charset=utf-8')
                ->request('GET', $detailsUrl);
            $stories[] = $this->getStories( $detailCrawler )[0];
        }
        return $stories;
    }

    protected function getStories(Crawler $crawler)
    {
        $allowedTags     = $this->site->getAllowedTags();
        $selector        = $this->site->getGrabSelector();
        $titleSelector   = $this->site->getTitleSelector();
        $contentSelector = $this->site->getContentSelector();
        $imageSelector   = $this->site->getImageTag();
        $content         = array( 'html' => '', 'title' => '', 'image' => null);
        $nodes           = $crawler->filter( $selector )->each(function ($node, $i) use( $allowedTags, $content, $titleSelector, $contentSelector, $imageSelector )
        {
            $isValid  = true;
            $imageUrl = null;
            if ( $imageSelector )
            {
                // find src attribute into image selector
                $imageSrc      = 'src';
                preg_match("/\[(.*?)\]/",$imageSelector, $searchSrc);
                if ( !empty($searchSrc) )
                {
                    $imageSrc      = $searchSrc[1];
                    $imageSelector = str_replace( $searchSrc[0], '', $imageSelector );
                }
                if ( strpos( $imageSelector, 'parent' ) === 0 )
                {
                    $imageSelector = trim( str_replace( 'parent', '', $imageSelector ) );
                    try {
                        $imageUrl = $node->siblings()->filter( $imageSelector )->first()->attr($imageSrc);
                    } catch ( \Exception $e)
                    {
                        // do something
                    }
                }
                else
                {
                    try {
                        $imageUrl = $node->filter( $imageSelector )->first()->attr($imageSrc);
                    } catch ( \Exception $e)
                    {
                        // do something
                    }
                }
                $content['image'] = $imageUrl;
            }
            if ( $titleSelector )
            {
                try {
                    $content['title'] = trim(strip_tags($node->filter( $titleSelector )->first()->html()));
                } catch ( \Exception $e)
                {
                    // echo $e->getMessage();
                }
            }
            if ( $contentSelector )
            {
                try {
                    $html = '';
                    foreach ($node->filter( $contentSelector ) as $child) {
                        $html .= $child->ownerDocument->saveHTML($child);
                    }
                    $content['html'] = trim(strip_tags($html, $allowedTags));
                } catch ( \Exception $e)
                {
                    // do something
                }
            }
            else
            {
                $content['html'] = trim(strip_tags($node->html(), $allowedTags));
            }
            if ( $content['html'] == '' && $content['title'] == '' )
            {
                $isValid = false;
            }
            return $isValid ? $content : $isValid;
        });

        return $nodes;
    }
}

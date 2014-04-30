<?php

namespace Reader\Bundle\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Reader\Bundle\ReaderBundle\Document\Site;

class LoadSiteData implements FixtureInterface
{
    private $_siteData = array(
        array(
            "identifier"   => "dtc",
            "title"        => "Dans ton chat",
            "shortTitle"   => "DTC",
            "url"          => "http://danstonchat.com",
            "urlPattern"   => "/latest/%d.html",
            "urlFirstPage" => 1,
            "urlStep"      => 1,
            "grabSelector" => ".item-content",
            "allowedTags"  => "<br><span>",
            "featured"     => true,
            "color"        => "99ff00"
        ),
    );

    /**
     * {@inheritDoc}
     */
    public function load( ObjectManager $manager )
    {
        foreach ( $this->_siteData as $siteData ) {
            $site = new Site();
            $site->setIdentifier( $siteData['identifier'] );
            $site->setTitle( $siteData['title'] );
            $site->setShortTitle( $siteData['shortTitle'] );
            $site->setUrl( $siteData['url'] );
            $site->setUrlPattern( $siteData['urlPattern'] );
            $site->setUrlFirstPage( $siteData['urlFirstPage'] );
            $site->setUrlStep( $siteData['urlStep'] );
            $site->setGrabSelector( $siteData['grabSelector'] );
            $site->setAllowedTags( $siteData['allowedTags'] );
            $site->setFeatured( $siteData['featured'] );
            $site->setColor( $siteData['color'] );
            $manager->persist( $site );
        }
        $manager->flush();
    }
}
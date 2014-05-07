<?php

namespace Reader\Bundle\ApiBundle\Controller;

use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\QueryParam;

class StoryController extends ApiAbstractController
{
    /**
     * @QueryParam(name="limit", requirements="\d+", description="limit", strict=false)
     * @QueryParam(name="sites", requirements="(\w+,?)+", description="sites", strict=false)
     * @param Request                              $request
     * @param \FOS\RestBundle\Request\ParamFetcher $paramFetcher
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cgetRandomAction( Request $request, ParamFetcher $paramFetcher )
    {
        $limit           = $paramFetcher->get( 'limit' ) ? $paramFetcher->get( 'limit' ) : 10;
        $sites           = explode( ',', $paramFetcher->get( 'sites' ) );
        $doctrine        = $this->get( 'doctrine_mongodb' );
        $storyRepository = $doctrine->getRepository( 'ReaderBundle:Story' );
        $stories         = $storyRepository->findAllBySite( $sites, $limit );

        return $this->getView(
            array(
                'stories' => $stories
            )
        );
    }

    /**
     * @QueryParam(name="limit", requirements="\d+", description="limit", strict=false)
     * @QueryParam(name="offset", requirements="\d+", description="offset", strict=false)
     * @QueryParam(name="sites", requirements="(\w+,?)+", description="sites", strict=false)
     * @param Request                              $request
     * @param \FOS\RestBundle\Request\ParamFetcher $paramFetcher
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cgetOrderedAction( Request $request, ParamFetcher $paramFetcher )
    {
        $memcached = $this->get( 'memcached' );
        $limit     = $paramFetcher->get( 'limit' ) ? $paramFetcher->get( 'limit' ) : 10;
        $offset    = $paramFetcher->get( 'offset' ) ? $paramFetcher->get( 'offset' ) : 0;
        $sites     = explode( ',', $paramFetcher->get( 'sites' ) );
        $cacheKey  = "stories_" . $limit . "_" . $offset . "_" . sha1( $paramFetcher->get( 'sites' ) );

        if ( $data = $memcached->get( $cacheKey ) ) {
            $stories = $data;
        }
        else
        {
            $doctrine        = $this->get( 'doctrine_mongodb' );
            $storyRepository = $doctrine->getRepository( 'ReaderBundle:Story' );

            $stories = $storyRepository->findAllBySite( $sites, $limit, $offset, false );
            $memcached->set( $cacheKey, $stories, 600 );
        }

        return $this->getView(
            array(
                'stories' => $stories
            )
        );
    }
}
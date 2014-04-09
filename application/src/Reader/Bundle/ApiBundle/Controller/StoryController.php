<?php

namespace Reader\Bundle\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Routing\ClassResourceInterface;
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
    public function cgetRandomAction(Request $request, ParamFetcher $paramFetcher)
    {
        $doctrine = $this->get('doctrine_mongodb');
        $storyRepository = $doctrine->getRepository('ReaderBundle:Story');
        $limit = $paramFetcher->get('limit') ? $paramFetcher->get('limit') : 10;
        $sites = explode(',', $paramFetcher->get('sites'));

        $stories = $storyRepository->findRandom($sites, $limit);

        return $this->getView(
            array(
                'stories' => $stories
            )
        );
    }
}
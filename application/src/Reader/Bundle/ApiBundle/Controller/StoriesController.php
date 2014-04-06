<?php

namespace Reader\Bundle\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class StoriesController extends Controller
{
    public function randomAction($limit)
    {
        $serializer = $this->get('jms_serializer');

        $doctrine = $this->get('doctrine_mongodb');
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $storyRepository = $doctrine->getRepository('ReaderBundle:Story');

        $stories = $storyRepository->findRandom(null, $limit);

        $response->setContent($serializer->serialize($stories, 'json'));

        return $response;
    }
}

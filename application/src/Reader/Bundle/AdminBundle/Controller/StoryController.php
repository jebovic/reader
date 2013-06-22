<?php

namespace Reader\Bundle\AdminBundle\Controller;

use Reader\Bundle\ReaderBundle\Document\Story;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StoryController extends Controller
{
    /**
     * Delete a story
     * @param string $id
     * @return mixed
     */
    public function deleteAction( $id )
    {
        $doctrine        = $this->get('doctrine_mongodb');
        $storyRepository = $doctrine->getRepository('ReaderBundle:Story');
        $story           = $storyRepository->find( $id );
        $response        = new Response();
        $response->headers->set( 'Content-Type', 'application/json' );

        if ( !is_null( $story ) )
        {
            $dm = $doctrine->getManager();
            $dm->remove($story);
            $dm->flush();

            $response->setContent( json_encode( array( 'success' => true )) );
            return $response;
        }
        $response->setContent( json_encode( array( 'success' => false )) );
        return $response;
    }
}
<?php

namespace Reader\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;

class DefaultController extends Controller
{
    public function testAction()
    {
        $response = new Response();

        $response->setContent( $this->renderView( 'ReaderAdminBundle:Default:test.html.twig', array() ) );

        return $response;
    }

    /**
     * Dashboard of manager interface
     * @return mixed
     */
    public function dashboardAction()
    {
        $response = new Response();
        $response->setPublic();
        $response->setMaxAge(3600);
        $response->setSharedMaxAge(3600);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->setContent( $this->renderView( 'ReaderAdminBundle:Default:dashboard.html.twig', array() ) );

        return $response;
    }

    /**
     * Get random stories
     *
     * @param int $limit
     *
     * @return mixed
     */
    public function storiesRandomAction( $limit = 10 )
    {
        $doctrine        = $this->get( 'doctrine_mongodb' );
        $storyRepository = $doctrine->getRepository( 'ReaderBundle:Story' );
        $response        = new Response();
        $response = new Response();
        $response->setPublic();
        $response->setMaxAge(10);
        $response->setSharedMaxAge(10);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->headers->set( 'Content-Type', 'application/json' );

        $stories = $storyRepository->findAllBySite( null, $limit );
        $result  = array('success' => true, 'content' => '', 'count' => '');

        $result['content'] = $this->renderView(
            'ReaderAdminBundle:Default:stories.html.twig',
            array(
                'stories' => $stories
            )
        );
        $response->setContent( json_encode( $result ) );

        return $response;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function loginAction( Request $request )
    {
        if ( $request->attributes->has( SecurityContext::AUTHENTICATION_ERROR ) ) {
            $error = $request->attributes->get( SecurityContext::AUTHENTICATION_ERROR );
        } else {
            $error = $request->getSession()->get( SecurityContext::AUTHENTICATION_ERROR );
        }

        return $this->render(
            'ReaderAdminBundle:Default:login.html.twig',
            array(
                'last_username' => $request->getSession()->get( SecurityContext::LAST_USERNAME ),
                'error'         => $error,
            )
        );
    }

    public function securityCheckAction()
    {
        // The security layer will intercept this request
    }

    public function logoutAction()
    {
        // The security layer will intercept this request
    }
}

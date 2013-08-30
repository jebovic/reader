<?php

namespace Reader\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;

class DefaultController extends Controller
{
    /**
     * Dashboard of manager interface
     * @return mixed
     */
    public function dashboardAction()
    {
        return $this->render('ReaderAdminBundle:Default:dashboard.html.twig', array());
    }

    /**
     * Get random stories
     * @param int $limit
     * @return mixed
     */
    public function storiesRandomAction( $limit = 10 )
    {
        $doctrine        = $this->get('doctrine_mongodb');
        $storyRepository = $doctrine->getRepository('ReaderBundle:Story');
        $response        = new Response();
        $response->headers->set( 'Content-Type', 'application/json' );

        $stories = $storyRepository->findRandom( null, $limit );
        $result  = array( 'success' => true, 'content' => '', 'count' => '' );

        $result['content'] = $this->render(
            'ReaderAdminBundle:Default:stories.html.twig',
            array(
                'stories' => $stories
            )
        )->getContent();
        $response->setContent( json_encode( $result ) );
        return $response;
    }

    public function loginAction(Request $request)
    {
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $request->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render(
            'ReaderAdminBundle:Default:login.html.twig',
            array(
                'last_username' => $request->getSession()->get(SecurityContext::LAST_USERNAME),
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

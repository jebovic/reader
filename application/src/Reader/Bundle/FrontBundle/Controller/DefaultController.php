<?php

namespace Reader\Bundle\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $limit = 500;
        $doctrine = $this->get('doctrine_mongodb');
        $storyRepository = $doctrine->getRepository('ReaderBundle:Story');
        $stories = $storyRepository->findRandom(null, $limit);

        return $this->render('ReaderFrontBundle:Default:index.html.twig', array('stories' => $stories));
    }

    public function loginAction(Request $request)
    {
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $request->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render(
            'ReaderFrontBundle:Default:index.html.twig',
            array(
                'last_username' => $request->getSession()->get(SecurityContext::LAST_USERNAME),
                'error' => $error,
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

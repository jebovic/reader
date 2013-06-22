<?php

namespace Reader\Bundle\GrabberBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ReaderGrabberBundle:Default:index.html.twig', array('name' => $name));
    }
}

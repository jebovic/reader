<?php

namespace Reader\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ReaderUserBundle:Default:index.html.twig', array('name' => $name));
    }
}

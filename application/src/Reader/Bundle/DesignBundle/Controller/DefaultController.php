<?php

namespace Reader\Bundle\DesignBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ReaderDesignBundle:Default:index.html.twig', array('name' => $name));
    }
}

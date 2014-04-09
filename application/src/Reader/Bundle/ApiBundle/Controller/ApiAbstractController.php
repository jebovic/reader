<?php

namespace Reader\Bundle\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;

class ApiAbstractController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @param mixed $data
     * @param string null $template
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getView($data = null, $template = null)
    {

        $template = (string)$template;
        $view = $this->view($data);

        if ($template) {
            $view->setTemplate($template);
        }

        $handleView = $this->handleView($view);

        return $handleView;
    }
}
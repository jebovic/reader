<?php

namespace Reader\Bundle\ApiBundle\Controller;

class SiteController extends ApiAbstractController
{
    /**
     * @internal param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAction( $id )
    {
        $doctrine = $this->get('doctrine_mongodb');
        $siteRepository = $doctrine->getRepository('ReaderBundle:Site');

        $site = $siteRepository->find($id);

        return $this->getView(
            array(
                'site' => $site
            )
        );
    }
    /**
     * @internal param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cgetAction()
    {
        $doctrine = $this->get('doctrine_mongodb');
        $siteRepository = $doctrine->getRepository('ReaderBundle:Site');

        $sites = $siteRepository->findAll();

        return $this->getView(
            array(
                'sites' => $sites
            )
        );
    }
}
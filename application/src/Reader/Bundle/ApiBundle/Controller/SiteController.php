<?php

namespace Reader\Bundle\ApiBundle\Controller;

class SiteController extends ApiAbstractController
{
    /**
     * @internal param \Symfony\Component\HttpFoundation\Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAction( $id )
    {
        $memcached = $this->get('memcached');
        if ( $data = $memcached->get("site_detail_$id") ) {
            $site = $data;
        }
        else
        {
            $doctrine = $this->get('doctrine_mongodb');
            $siteRepository = $doctrine->getRepository('ReaderBundle:Site');

            $site = $siteRepository->find($id);
            $memcached->set("site_detail_$id", $site, 86400);
        }

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
        $memcached = $this->get('memcached');
        if ( $data = $memcached->get("site_list") ) {
            $sites = $data;
        }
        else
        {
            $doctrine = $this->get('doctrine_mongodb');
            $siteRepository = $doctrine->getRepository('ReaderBundle:Site');

            $sites = $siteRepository->findAll();
            $memcached->set("site_list", $sites, 86400);
        }

        return $this->getView(
            array(
                'sites' => $sites
            )
        );
    }
}
<?php

namespace Reader\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Reader\Bundle\ReaderBundle\Document\Site;
use Reader\Bundle\AdminBundle\Form\Type\SiteType;

class SiteController extends Controller
{
    /**
     * Index of sites management
     * @return mixed
     */
    public function indexAction()
    {
        $doctrine           = $this->get('doctrine_mongodb');
        $siteRepository     = $doctrine->getRepository('ReaderBundle:Site');
        $sites              = $siteRepository->findBy( array(), array( 'identifier' => 'ASC' ) );
        $categoryRepository = $doctrine->getRepository('ReaderBundle:Category');
        $categories         = $categoryRepository->findBy( array(), array( 'identifier' => 'ASC' ) );

        return $this->render(
            'ReaderAdminBundle:Site:index.html.twig',
            array(
                'sites' => $sites,
                'categories' => $categories
            )
        );
    }

    /**
     * Add a site in the catalog
     * @param Request $request
     * @return mixed
     */
    public function addAction(Request $request)
    {
        $site = new Site();

        if ( $request->isMethod('POST') ) {
            $form = $this->createForm(new SiteType(), $site );
            $form->submit( $request );
            if ($form->isValid()) {
                // Persist site in DB
                $doctrine = $this->get('doctrine_mongodb')->getManager();
                $doctrine->persist( $site );
                $doctrine->flush();

                $this->get('session')->getFlashBag()->add( 'successSiteAdd', 1 );
                return $this->redirect( $this->generateUrl( 'reader_admin_site' ) );
            }
        }
        else
        {
            $form = $this->createForm(new SiteType(), $site );
        }

        return $this->render(
            'ReaderAdminBundle:Site:add.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

    /**
     * Update a site in the catalog
     * @param Request $request
     * @param string $id
     * @return mixed
     */
    public function updateAction(Request $request, $id)
    {
        $doctrine       = $this->get('doctrine_mongodb');
        $siteRepository = $doctrine->getRepository('ReaderBundle:Site');
        $site           = $siteRepository->find( $id );

        if ( !is_null( $site ) )
        {
            $form = $this->createForm(new SiteType(), $site );
            if ( $request->isMethod('POST') )
            {
                $form->submit( $request );
                if ($form->isValid()) {
                    // Persist site in DB
                    $doctrine = $this->get('doctrine_mongodb')->getManager();
                    $doctrine->flush();

                    $this->get('session')->getFlashBag()->add( 'successSiteUpdate', 1 );
                    return $this->redirect( $this->generateUrl( 'reader_admin_site' ) );
                }
            }
            else
            {
                return $this->render(
                    'ReaderAdminBundle:Site:update.html.twig',
                    array(
                        'form' => $form->createView()
                    )
                );
            }
        }
        return $this->redirect( $this->generateUrl('reader_admin_site') );
    }
}

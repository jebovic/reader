<?php

namespace Reader\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Reader\Bundle\ReaderBundle\Document\Category;
use Reader\Bundle\AdminBundle\Form\Type\CategoryType;

class CategoryController extends Controller
{
    /**
     * Add a category in the catalog
     * @param Request $request
     * @return mixed
     */
    public function addAction(Request $request)
    {
        $category = new Category();

        if ( $request->isMethod('POST') ) {
            $form = $this->createForm(new CategoryType(), $category );
            $form->submit( $request );
            if ($form->isValid()) {
                // Persist site in DB
                $doctrine = $this->get('doctrine_mongodb')->getManager();
                $doctrine->persist( $category );
                $doctrine->flush();

                $this->get('session')->getFlashBag()->add( 'successCategoryAdd', 1 );
                return $this->redirect( $this->generateUrl( 'reader_admin_site' ) );
            }
        }
        else
        {
            $form = $this->createForm(new CategoryType(), $category );
        }

        return $this->render(
            'ReaderAdminBundle:Category:add.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

    /**
     * Update a category
     * @param Request $request
     * @param string $id
     * @return mixed
     */
    public function updateAction(Request $request, $id)
    {
        $doctrine           = $this->get('doctrine_mongodb');
        $categoryRepository = $doctrine->getRepository('ReaderBundle:Category');
        $category           = $categoryRepository->find( $id );

        if ( !is_null( $category ) )
        {
            $form = $this->createForm(new CategoryType(), $category );
            if ( $request->isMethod('POST') )
            {
                $form->submit( $request );
                if ($form->isValid()) {
                    // Persist site in DB
                    $doctrine = $this->get('doctrine_mongodb')->getManager();
                    $doctrine->flush();

                    $this->get('session')->getFlashBag()->add( 'successCategoryUpdate', 1 );
                    return $this->redirect( $this->generateUrl( 'reader_admin_site' ) );
                }
            }
            else
            {
                return $this->render(
                    'ReaderAdminBundle:Category:update.html.twig',
                    array(
                        'form' => $form->createView()
                    )
                );
            }
        }
        return $this->redirect( $this->generateUrl('reader_admin_site') );
    }
}
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
            $notifier = $this->get('reader_notifier');
            $form     = $this->createForm(new CategoryType(), $category );
            $form->submit( $request );
            if ($form->isValid()) {
                // Persist site in DB
                $doctrine = $this->get('doctrine_mongodb')->getManager();
                $doctrine->persist( $category );
                $doctrine->flush();

                $notifier->notify( 'Category added', $category->getName() . ' has been created' );

                return $this->redirect( $this->generateUrl( 'reader_admin_site' ) );
            } else {
                $notifier->notifyInvalidForm();
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
                $notifier = $this->get('reader_notifier');
                $form->submit( $request );
                if ($form->isValid()) {
                    // Persist site in DB
                    $doctrine = $this->get('doctrine_mongodb')->getManager();
                    $doctrine->flush();

                    $notifier->notify( 'Category updated', $category->getName() . ' has been updated' );
                    return $this->redirect( $this->generateUrl( 'reader_admin_site' ) );
                } else {
                    $notifier->notifyInvalidForm();
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

    /**
     * Delete a category
     * @param string $id
     * @return mixed
     */
    public function deleteAction($id)
    {
        $doctrine           = $this->get('doctrine_mongodb');
        $categoryRepository = $doctrine->getRepository('ReaderBundle:Category');
        $category           = $categoryRepository->find( $id );

        if ( !is_null( $category ) )
        {
            $dm             = $this->get('doctrine_mongodb')->getManager();
            $siteRepository = $doctrine->getRepository('ReaderBundle:Site');
            $sites          = $siteRepository->findAll();
            $notifier       = $this->get('reader_notifier');

            if ( !is_null( $sites ) && !empty( $sites ) )
            {
                foreach( $sites as $site )
                {
                    $site->removeCategory($category);
                    $dm->flush();
                    $notifier->notify( 'Site updated', $site->getTitle() . ' categories updated' );
                }
            }
            $categoryName = $category->getName();
            $dm->remove($category);
            $dm->flush();
            $notifier->notify( 'Category deleted', $categoryName . ' has been deleted' );
        }
        return $this->redirect( $this->generateUrl('reader_admin_site') );
    }
}

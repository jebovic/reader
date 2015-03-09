<?php

namespace Reader\Bundle\AdminBundle\Controller;

use Reader\Bundle\ReaderBundle\Document\Story;
use Reader\Bundle\ReaderBundle\ReaderBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Reader\Bundle\ReaderBundle\Document\Site;
use Reader\Bundle\AdminBundle\Form\Type\SiteType;
use GuzzleHttp\Client as HttpClient;

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
     * Iframe of site
     * @param $id
     * @param $page
     * @return mixed
     * @throws \Doctrine\ODM\MongoDB\LockException
     */
    public function iframeAction( $id, $page )
    {
        $this->get('profiler')->disable();
        $doctrine       = $this->get('doctrine_mongodb');
        $siteRepository = $doctrine->getRepository('ReaderBundle:Site');
        $site           = $siteRepository->find( $id );

        $grabber = $this->get('reader_grabber');
        $iframeUrl = $grabber
            ->init($site, $page)
            ->getUrl();

        if ( $proxyUrl = $this->container->getParameter('reader_proxy') )
        {
            $client = new HttpClient(array(
            'defaults' => array(
                'proxy' => $proxyUrl
                )
            ));
        }
        else
        {
            $client = new HttpClient();
        }
        
        $content = $client->get( $iframeUrl )->getBody();
        $response = new Response();
        $response->setContent($content);
        return $response;
    }

    /**
     * View site sandbox
     * @param string $id
     * @return mixed
     */
    public function sandboxAction( $id )
    {
        $doctrine       = $this->get('doctrine_mongodb');
        $siteRepository = $doctrine->getRepository('ReaderBundle:Site');
        $site           = $siteRepository->find( $id );

        if ( !is_null( $site ) )
        {
            return $this->render(
                'ReaderAdminBundle:Site:sandbox.html.twig',
                array(
                    'site'         => $site
                )
            );
        }
        return $this->redirect( $this->generateUrl('reader_admin_site') );
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
            $notifier = $this->get('reader_notifier');
            $form     = $this->createForm(new SiteType(), $site );
            $form->submit( $request );
            if ($form->isValid()) {
                // Persist site in DB
                $doctrine = $this->get('doctrine_mongodb')->getManager();

                $site->upload();

                $doctrine->persist( $site );
                $doctrine->flush();

                $notifier->notify( 'Site added', $site->getTitle() . ' has been created' );

                return $this->redirect( $this->generateUrl( 'reader_admin_site' ) );
            } else {
                $notifier->notifyInvalidForm();
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
        $notifier       = $this->get('reader_notifier');

        if ( !is_null( $site ) )
        {
            $form = $this->createForm(new SiteType(), $site );
            if ( $request->isMethod('POST') )
            {
                $form->submit( $request );
                if ($form->isValid()) {
                    // Persist site in DB
                    $doctrine = $this->get('doctrine_mongodb')->getManager();

                    $site->upload();

                    $doctrine->flush();
                    $notifier->notify( 'Site updated', $site->getTitle() . ' has been updated' );

                    return $this->redirect( $this->generateUrl( 'reader_admin_site' ) );
                } else {
                    $notifier->notifyInvalidForm( $form->getErrors() );
                }
            }
            else
            {
                return $this->render(
                    'ReaderAdminBundle:Site:update.html.twig',
                    array(
                        'site' => $site,
                        'form' => $form->createView()
                    )
                );
            }
        }
        return $this->redirect( $this->generateUrl('reader_admin_site') );
    }

    /**
     * Delete a site from the catalog
     * @param string $id
     * @return mixed
     */
    public function deleteAction($id)
    {
        $doctrine       = $this->get('doctrine_mongodb');
        $siteRepository = $doctrine->getRepository('ReaderBundle:Site');
        $site           = $siteRepository->find( $id );
        $notifier       = $this->get('reader_notifier');

        if ( !is_null( $site ) )
        {
            $siteTitle       = $site->getTitle();
            $storyRepository = $doctrine->getRepository('ReaderBundle:Story');
            $storiesCount    = $storyRepository->countBySite( $site->getId() );
            $dm              = $doctrine->getManager();
            if ( $storiesCount > 0 )
            {
                $stories = $storyRepository->findBySite( $site->getId() );
                foreach ( $stories as $story )
                {
                    $dm->remove($story);
                    $dm->flush();
                }
            }
            $dm->remove($site);
            $dm->flush();
            $notifier->notify( 'Site deleted', $siteTitle . ' and its stories have been deleted' );
        } else {
            $notifier->notify( 'Site does not exist', 'The site you tried to delete does not exist', 'error' );
        }
        return $this->redirect( $this->generateUrl('reader_admin_site') );
    }

    /**
     * View site details
     * @param string $id
     * @return mixed
     */
    public function viewAction( $id )
    {
        $doctrine       = $this->get('doctrine_mongodb');
        $siteRepository = $doctrine->getRepository('ReaderBundle:Site');
        $site           = $siteRepository->find( $id );

        if ( !is_null( $site ) )
        {
            $storyRepository = $doctrine->getRepository('ReaderBundle:Story');
            $storiesCount    = $storyRepository->countBySite( $site->getId() );
            return $this->render(
                'ReaderAdminBundle:Site:view.html.twig',
                array(
                    'site'         => $site,
                    'storiesCount' => $storiesCount
                )
            );
        }
        return $this->redirect( $this->generateUrl('reader_admin_site') );
    }

    /**
     * Grab site stories
     * @param string $id
     * @param string $page
     * @return mixed
     */
    public function grabAction($id, $page)
    {
        $doctrine       = $this->get('doctrine_mongodb');
        $siteRepository = $doctrine->getRepository('ReaderBundle:Site');
        $site           = $siteRepository->find( $id );

        $response = new Response();
        $response->headers->set( 'Content-Type', 'application/json' );
        if ( !is_null( $site ) )
        {
            $grabber = $this->get('reader_grabber');
            $stories = $grabber
                ->init($site, $page)
                ->grab();
            if ( $stories && !empty($stories) )
            {
                $count   = 0;
                $now     = time();
                $manager = $doctrine->getManager();
                foreach( $stories as $position => $story )
                {
                    if ( $story === false ) continue;
                    $storySum        = sha1( $story['title'] . '||' . $story['html'] );
                    $storyRepository = $doctrine->getRepository('ReaderBundle:Story');
                    $storyExists     = $storyRepository->findOneBy( array('textSum' => $storySum ) );

                    if ( is_null( $storyExists ) )
                    {
                        $storyDocument = new Story();
                        $storyDocument->setGrabbed( $now );
                        $storyDocument->setPage( $page );
                        $storyDocument->setSite( $site );
                        $storyDocument->setPosition( $position );
                        $storyDocument->setText( $story['html'] );
                        $storyDocument->setTitle( $story['title'] );
                        $storyDocument->setTextSum( $storySum );
                        $storyDocument->setRandomizer();

                        $imageUrl = $story['image'];
                        if ( !is_null( $imageUrl ) && $imageUrl != '' )
                        {
                            if ( strpos( $imageUrl, 'http' ) === 0)
                            {
                                $storyDocument->setImage( $story['image'] );
                            }
                            else
                            {
                                $storyDocument->setImage( $site->getUrl() . '/' . $story['image'] );
                            }
                        }

                        $manager->persist( $storyDocument );
                        $manager->flush();

                        $count++;
                    }
                }
                $response->setContent( json_encode( array( 'success' => true, 'count' => $count )) );
                return $response;
            }
        }
        $response->setContent( json_encode( array( 'success' => false )) );
        return $response;
    }

    /**
     * Get stories
     * @param string $id
     * @param string $page
     * @param bool $returnCount
     * @param int $limit
     * @return mixed
     */
    public function storiesAction($id, $page, $returnCount = false, $limit = 10 )
    {
        $doctrine       = $this->get('doctrine_mongodb');
        $siteRepository = $doctrine->getRepository('ReaderBundle:Site');
        $site           = $siteRepository->find( $id );
        $response       = new Response();
        $response->headers->set( 'Content-Type', 'application/json; charset=utf-8;' );
        if ( !is_null( $site ) )
        {
            $storyRepository = $doctrine->getRepository('ReaderBundle:Story');
            $offset  = ($page - 1) * $limit;
            $stories = $storyRepository->findBySite( $site->getId(), $offset, $limit );
            $result  = array( 'success' => true, 'content' => '', 'count' => '' );

            if ( $returnCount )
            {
                $result['count'] = $storyRepository->countBySite( $site->getId() );
            }

            $result['content'] = $this->render(
                'ReaderAdminBundle:Site:stories.html.twig',
                array(
                    'stories' => $stories
                )
            )->getContent();
            $response->setContent( json_encode( $result ) );
            return $response;
        }

        $response->setContent( json_encode( array( 'success' => false ) ) );
        return $response;
    }

    /**
     * Get random stories
     * @param string $id
     * @param int $limit
     * @return mixed
     */
    public function storiesRandomAction($id, $limit = 10 )
    {
        $doctrine       = $this->get('doctrine_mongodb');
        $siteRepository = $doctrine->getRepository('ReaderBundle:Site');
        $site           = $siteRepository->find( $id );
        $response       = new Response();
        $response->headers->set( 'Content-Type', 'application/json' );
        if ( !is_null( $site ) )
        {
            $storyRepository = $doctrine->getRepository('ReaderBundle:Story');

            $stories = $storyRepository->findAllBySite( $site->getId(), $limit );
            $result  = array( 'success' => true, 'content' => '', 'count' => '' );

            $result['content'] = $this->render(
                'ReaderAdminBundle:Site:stories.html.twig',
                array(
                    'stories' => $stories
                )
            )->getContent();
            $response->setContent( json_encode( $result ) );
            return $response;
        }

        $response->setContent( json_encode( array( 'success' => false ) ) );
        return $response;
    }

    /**
     * Purge stories for the given website
     * @param string $id
     * @return mixed
     */
    public function purgeStoriesAction($id)
    {
        $notifier       = $this->get('reader_notifier');
        $doctrine       = $this->get('doctrine_mongodb');
        $siteRepository = $doctrine->getRepository('ReaderBundle:Site');
        $site           = $siteRepository->find( $id );

        if ( !is_null( $site ) )
        {
            $siteTitle       = $site->getTitle();
            $storyRepository = $doctrine->getRepository('ReaderBundle:Story');
            $storiesCount    = $storyRepository->countBySite( $site->getId() );
            $dm              = $doctrine->getManager();
            if ( $storiesCount > 0 )
            {
                $stories = $storyRepository->findBySite( $site->getId() );
                foreach ( $stories as $story )
                {
                    $dm->remove($story);
                    $dm->flush();
                }
            }
            else
            {
                $notifier->notify( 'No stories to remove', 'error' );
            }
            $notifier->notify( 'Stories have been deleted', $siteTitle . ' stories have been deleted' );
        }
        return $this->redirect( $this->generateUrl('reader_admin_site_view', array( 'id' => $id) ) );
    }
}

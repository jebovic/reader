<?php

namespace Reader\Bundle\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Reader\Bundle\AdminBundle\Form\Type\RegistrationType;
use Reader\Bundle\AdminBundle\Form\Model\Registration;
use Reader\Bundle\AdminBundle\Form\Type\UserEditType;

class UserController extends Controller
{
    /**
     * Index of users management
     * @return mixed
     */
    public function indexAction()
    {
        $doctrine           = $this->get('doctrine_mongodb');
        $userRepository     = $doctrine->getRepository('ReaderUserBundle:User');
        $usersCount         = $userRepository->count();

        return $this->render(
            'ReaderAdminBundle:User:index.html.twig',
            array(
                'usersCount' => $usersCount
            )
        );
    }

    /**
     * Get users list
     * @param string $page
     * @param int $limit
     * @return mixed
     */
    public function listAction( $page, $limit = 10 )
    {
        $offset         = ( $limit * $page ) - $limit;
        $doctrine       = $this->get('doctrine_mongodb');
        $userRepository = $doctrine->getRepository('ReaderUserBundle:User');
        $users          = $userRepository->findBy( array(), array( 'email' => 'ASC'), $limit, $offset );
        $response       = new Response();

        $response->headers->set( 'Content-Type', 'application/json' );
        if ( !empty( $users ) )
        {
            $result  = array( 'success' => true, 'content' => '' );

            $result['content'] = $this->render(
                'ReaderAdminBundle:User:list.html.twig',
                array(
                    'users' => $users
                )
            )->getContent();
            $response->setContent( json_encode( $result ) );
            return $response;
        }

        $response->setContent( json_encode( array( 'success' => false ) ) );
        return $response;
    }

    /**
     * Add a user
     * @param Request $request
     * @return mixed
     */
    public function addAction(Request $request)
    {
        if ( $request->isMethod('POST') ) {
            $notifier = $this->get('reader_notifier');
            $form     = $this->createForm(new RegistrationType(), new Registration() );
            $form->submit( $request );
            if ($form->isValid()) {
                // Persist user in DB
                $user = $form->getData()->getUser();
                $user->setCreated( time() );
                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($user);
                $password = $encoder->encodePassword( $user->getPassword(), $user->getSalt());
                $user->setPassword($password);
                $doctrine = $this->get('doctrine_mongodb')->getManager();
                $doctrine->persist( $user );
                $doctrine->flush();
                $notifier->notify( 'User added', $user->getEmail() . ' has been created' );

                return $this->redirect( $this->generateUrl( 'reader_admin_user' ) );
            } else {
                $notifier->notifyInvalidForm( $form->getErrors() );
            }
        }
        else
        {
            $form = $this->createForm(new RegistrationType(), new Registration());
        }

        return $this->render(
            'ReaderAdminBundle:User:add.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

    /**
     * Update a user
     * @param Request $request
     * @param string $id
     * @return mixed
     */
    public function updateAction(Request $request, $id)
    {
        $doctrine       = $this->get('doctrine_mongodb');
        $userRepository = $doctrine->getRepository('ReaderUserBundle:User');
        $user           = $userRepository->find( $id );

        if ( !is_null( $user ) )
        {
            $lastPassword = $user->getPassword();
            $form = $this->createForm(new UserEditType(), $user);
            if ( $request->isMethod('POST') )
            {
                $notifier = $this->get('reader_notifier');
                $form->submit( $request );
                if ($form->isValid()) {
                    // Persist site in DB
                    if ( null != $form->getData()->getPassword() )
                    {
                        $factory = $this->get('security.encoder_factory');
                        $encoder = $factory->getEncoder($user);
                        $password = $encoder->encodePassword( $user->getPassword(), $user->getSalt());
                        $user->setPassword($password);
                    }
                    else
                    {
                        $user->setPassword( $lastPassword );
                    }

                    $doctrine = $this->get('doctrine_mongodb')->getManager();
                    $doctrine->flush();

                    $notifier->notify( 'User updated', $user->getEmail() . ' has been updated' );
                    return $this->redirect( $this->generateUrl( 'reader_admin_user' ) );
                } else {
                    $notifier->notifyInvalidForm( $form->getErrors() );
                }
            }
            return $this->render(
                'ReaderAdminBundle:User:update.html.twig',
                array(
                    'user' => $user,
                    'form' => $form->createView()
                )
            );
        }
        return $this->redirect( $this->generateUrl('reader_admin_user') );
    }

    /**
     * Delete a user
     * @param string $id
     * @return mixed
     */
    public function deleteAction($id)
    {
        $doctrine       = $this->get('doctrine_mongodb');
        $userRepository = $doctrine->getRepository('ReaderUserBundle:User');
        $user           = $userRepository->find( $id );

        if ( !is_null( $user ) )
        {
            $dm       = $this->get('doctrine_mongodb')->getManager();
            $notifier = $this->get('reader_notifier');
            $userName = $user->getEmail();

            $dm->remove($user);
            $dm->flush();

            $notifier->notify( 'User deleted', $userName . ' has been deleted' );
        }
        return $this->redirect( $this->generateUrl('reader_admin_user') );
    }

    /**
     * View user details
     * @param string $id
     * @return mixed
     */
    public function viewAction( $id )
    {
        $doctrine       = $this->get('doctrine_mongodb');
        $userRepository = $doctrine->getRepository('ReaderUserBundle:User');
        $user           = $userRepository->find( $id );

        if ( !is_null( $user ) )
        {
            return $this->render(
                'ReaderAdminBundle:User:view.html.twig',
                array(
                    'user' => $user
                )
            );
        }
        return $this->redirect( $this->generateUrl('reader_admin_user') );
    }
}

<?php

namespace Reader\Bundle\UserBundle\Auth;

use Reader\Bundle\UserBundle\Document\User;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUserProvider;

class OAuthProvider extends OAuthUserProvider
{
    protected $container, $documentManager;

    public function __construct($service_container, $documentManager )
    {
        $this->documentManager = $documentManager;
        $this->container = $service_container;
    }
    public function loadUserByUsername( $email )
    {
        $userRepository = $this->documentManager->getRepository('ReaderUserBundle:User');
        $user = $userRepository->findOneBy(array('email' => $email));

        if ( is_null($user) )
        {
            $user = new User();
            $user->setCreated( time() );
            $user->setEmail( $email );
            $user->setRoles( array( 'ROLE_USER' ) );

            $factory = $this->container->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $password = $encoder->encodePassword(md5(uniqid()), $user->getSalt());
            $user->setPassword($password);

            $this->documentManager->persist($user);
            $this->documentManager->flush();
            $this->documentManager->refresh($user);

        }
        return $user;
    }

    public function loadUserByOAuthUserResponse( UserResponseInterface $response )
    {
        $email = $response->getEmail();
        return $this->loadUserByUsername( $email );
    }

    public function supportsClass( $class )
    {
        return $class === 'Reader\\Bundle\\UserBundle\\Document\\User';
    }
}
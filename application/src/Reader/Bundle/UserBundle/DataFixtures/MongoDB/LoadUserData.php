<?php

namespace Reader\Bundle\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Reader\Bundle\UserBundle\Document\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load( ObjectManager $manager )
    {
        $userAdmin = new User();
        $userAdmin->setEmail( 'admin@reader.loc' );
        $userAdmin->setRoles( array('ROLE_ADMIN') );
        $userAdmin->setCreated( time() );

        $password = 'adminreader';
        $factory  = $this->container->get( 'security.encoder_factory' );
        $encoder  = $factory->getEncoder( $userAdmin );
        $password = $encoder->encodePassword( $password, $userAdmin->getSalt() );
        $userAdmin->setPassword( $password );

        $manager->persist( $userAdmin );
        $manager->flush();
    }
}
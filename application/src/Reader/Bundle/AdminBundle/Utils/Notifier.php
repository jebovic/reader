<?php

namespace Reader\Bundle\AdminBundle\Utils;

use Symfony\Component\DependencyInjection\Container;

class Notifier {

    private $container;

    /**
     * @param Container $container
     */
    public function __construct( Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $title
     * @param string $content
     * @param string $type
     * @param string $icon
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function notify( $title = '', $content = '', $type = 'success', $icon = '' )
    {
        if ( $title === '' && $content === '' )
        {
            throw new \InvalidArgumentException( 'Notifier : you have to set at least a content or a title.' );
        }
        $this->container->get('session')->getFlashBag()->add( 'notify', array(
                'type'    => $type,
                'title'   => $title,
                'content' => $content,
                'icon'    => $icon
            ) );
        return true;
    }

    /**
     * @return bool
     */
    public function notifyInvalidForm( $formErrors = null )
    {
        if ( !is_null( $formErrors ) && !empty( $formErrors ) )
        {
            $displayedErrors = array();
            foreach ( $formErrors as $error )
            {
                $displayedErrors[] = $error->getMessageTemplate();
            }
        }
        $this->notify( 'Invalid fields', 'Some data you fill in the form are not valid<br>' . implode('<br>',$displayedErrors), 'error' );
        return true;
    }
}
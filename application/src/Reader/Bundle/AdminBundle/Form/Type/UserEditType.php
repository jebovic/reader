<?php

namespace Reader\Bundle\AdminBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserEditType extends UserType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('update_user'),
            'data_class' => 'Reader\Bundle\UserBundle\Document\User',
        ));
    }

    public function getName()
    {
        return 'userEdit';
    }
}

?>
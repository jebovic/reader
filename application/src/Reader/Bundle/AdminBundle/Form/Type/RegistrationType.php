<?php

namespace Reader\Bundle\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('user', new UserType( array('ROLE_USER' => 'User', 'ROLE_ADMIN' => 'Admin') ) );
        $builder->add('terms', 'checkbox', array('property_path' => 'termsAccepted'));
    }

    public function getName()
    {
        return 'registration';
    }
}
<?php

namespace Reader\Bundle\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    protected $roles;

    public function __construct()
    {
        $this->roles = array('ROLE_USER' => 'User', 'ROLE_ADMIN' => 'Admin');
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add( 'email', 'email', array(
                'label' => 'Email',
                'attr'  => array(
                    'help' => '* required.'
                ),
            ));
        $builder->add( 'password', 'repeated', array(
                'first_name' => 'password',
                'second_name' => 'confirm',
                'type' => 'password',
                'attr'  => array(
                    'help' => '* required.'
                ),
            ));
        $builder->add( 'roles', 'choice', array(
            'label' => 'Roles',
            'multiple' => true,
            'choices' => $this->roles
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('new_user'),
            'data_class' => 'Reader\Bundle\UserBundle\Document\User',
        ));
    }

    public function getName()
    {
        return 'user';
    }
}

?>
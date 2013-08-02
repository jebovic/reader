<?php

namespace Reader\Bundle\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Reader\Bundle\ReaderBundle\Repository\CategoryRepository;

class SiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add( 'identifier', 'text', array(
            'label' => 'Identifier',
            'attr'  => array(
                'help' => '* required.'
            ),
        ));
        $builder->add( 'title', 'text', array(
            'label' => 'Title',
            'attr'  => array(
                'help' => '* required.'
            ),
        ));
        $builder->add( 'shortTitle', 'text', array(
            'label' => 'Short title',
            'attr'  => array(
                'help' => '* required.'
            ),
        ));
        $builder->add( 'url', 'text', array(
            'label' => 'Site URL',
            'attr'  => array(
                'help' => '* required.'
            ),
        ));
        $builder->add( 'urlPattern', 'text', array(
            'label' => 'URL pattern',
            'attr'  => array(
                'help' => '* required.'
            ),
        ));
        $builder->add( 'urlFirstPage', 'integer', array(
            'label' => 'URL first page',
            'attr'  => array(
                'help' => '* required.'
            ),
        ));
        $builder->add( 'urlStep', 'integer', array(
            'label' => 'URL step',
            'attr'  => array(
                'help' => '* required.'
            ),
        ));
        $builder->add( 'grabSelector', 'text', array(
            'label' => 'Grab CSS selector',
            'attr'  => array(
                'help' => '* required.'
            ),
        ));
        $builder->add( 'titleSelector', 'text', array(
                'label' => 'Title CSS selector',
                'attr'  => array(
                    'help' => '* required.'
                ),
                'required' => false
            ));
        $builder->add( 'contentSelector', 'text', array(
            'label' => 'Content CSS selector',
            'attr'  => array(
                'help' => '* required.'
            ),
            'required' => false
        ));
        $builder->add( 'allowedTags', 'text', array(
            'label' => 'Allowed tags',
            'required' => false
        ));
        $builder->add( 'imageTag', 'text', array(
            'label' => 'Image tag',
            'required' => false
        ));
        $builder->add( 'categories', 'document', array(
            'label' => 'Categories',
            'property' => 'name',
            'multiple' => true,
            'expanded' => false,
            'class' => 'Reader\Bundle\ReaderBundle\Document\Category',
            'query_builder' => function( CategoryRepository $er ) {
                return $er->createQueryBuilder('c')
                    ->sort('c.identifier', 'ASC');
            },
            'attr'  => array(
                'help'  => '* required.'
            ),
            'required' => false
        ));
        $builder->add( 'featured', 'checkbox', array(
            'label' => 'Featured ?',
            'required' => false
        ));

        $builder->add( 'logo', 'file', array(
                'label' => 'Logo',
                'required' => false
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'Reader\Bundle\ReaderBundle\Document\Site'
            ));
    }

    public function getName()
    {
        return 'site';
    }
}

?>
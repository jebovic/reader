<?php

namespace Reader\Bundle\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Reader\Bundle\ReaderBundle\Repository\CategoryRepository;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add( 'identifier', 'text', array(
                'label' => 'Identifier',
                'attr'  => array(
                    'help' => '* required.'
                ),
            ));
        $builder->add( 'name', 'text', array(
                'label' => 'Name',
                'attr'  => array(
                    'help' => '* required.'
                ),
            ));
        $builder->add( 'parent', 'document', array(
                'label' => 'Parent',
                'property' => 'name',
                'class' => 'Reader\Bundle\ReaderBundle\Document\Category',
                'query_builder' => function( CategoryRepository $er ) {
                    return $er->createQueryBuilder('c')
                        ->sort('c.identifier', 'ASC');
                },
                'attr'  => array(
                    'help'  => '* required.',
                    'data-placeholder' => 'Choose a category...'
                ),
                'required' => false
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'Reader\Bundle\ReaderBundle\Document\Category'
            ));
    }

    public function getName()
    {
        return 'category';
    }
}

?>
<?php
// src/Sdz/BlogBundle/Form/ArticleType.php

namespace Sdz\Bundle\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
class ArticleType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('date',        'date')
      ->add('titre',       'text')
      ->add('contenu',     'textarea')
      ->add('auteur',      'text')
      ->add('publication', 'checkbox', array('required' => false))
      ->add('image',        new ImageType())
      ->add('categories', 'collection', array('type' => new CategorieType(),
                                              'allow_add'    => true,
                                              'allow_delete' => true))
    ;
  }

  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'data_class' => 'Sdz\Bundle\BlogBundle\Entity\Article'
    ));
  }

  public function getName()
  {
    return 'sdz_blogbundle_articletype';
  }
}
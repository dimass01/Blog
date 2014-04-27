<?php
// src/Sdz/BlogBundle/Form/ArticleEditType.php

namespace Sdz\Bundle\BlogBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;

class ArticleEditType extends ArticleType
{
 
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      parent::buildForm($builder, $options);
    // On supprime celui qu'on ne veut pas dans le formulaire de modification
    $builder->remove('date');
    
  }

 
  public function getName()
  {
    return 'sdz_blogbundle_articleedittype';
  }
}
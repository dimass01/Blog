<?php
// src/Sdz/BlogBundle/Form/ArticleType.php

namespace Sdz\Bundle\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
class ArticleType extends AbstractType
{
    
   private $listCategories;
    
    public function __construct(array $listCategories)
    {

         $this->listCategories =$listCategories;

    }  
          
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $listCategories= $this->listCategories;
      $builder
        ->add('date',        'date')
        ->add('titre',       'text')
        ->add('contenu',     'textarea')
        ->add('auteur',      'text')
        ->add('publication', 'checkbox', array('required' => false))
        ->add('image',        new ImageType())
       /* ->add('categories', 'collection', array('type' => new CategorieType(),
                                                'allow_add'    => true,
                                                'allow_delete' => true))*/
        ->add('categories', 'entity', array(
                      'class'    => 'SdzBlogBundle:Categorie',
                      'property' => 'nom',
                      'multiple' => true,
                      'expanded' => true,
                      'query_builder' => function(\Sdz\Bundle\BlogBundle\Entity\CategorieRepository $r) use($listCategories) {
                                           return $r->getSelectedCategories($listCategories);
                                          }

            )
  );
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
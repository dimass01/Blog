<?php

namespace Sdz\Bundle\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Article
 *
 * @ORM\Table(name="sdz_article")
 * @ORM\Entity(repositoryClass="Sdz\Bundle\BlogBundle\Entity\ArticleRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Article
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     * @Assert\DateTime()
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     * @Assert\Length(min=10,max=100)
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     * @Assert\Length(min=2,max=100)
     * @ORM\Column(name="auteur", type="string", length=255)
     */
    private $auteur;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="contenu", type="text")
     */
    private $contenu;
  
    /**
    * @ORM\Column(name="publication", type="boolean" ,  nullable=true)
    */
    private $publication;
    
   /**
   * @ORM\OneToOne(targetEntity="Sdz\Bundle\BlogBundle\Entity\Image", cascade={"persist", "remove"})
   */
    private $image;
    
     /**
      * @Assert\Valid()
      * @ORM\ManyToMany(targetEntity="Sdz\Bundle\BlogBundle\Entity\Categorie", cascade={"persist"})
      */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="Sdz\Bundle\BlogBundle\Entity\Commentaire", mappedBy="article")
     */
    private $commentaires; // Ici commentaires prend un « s », car un article a plusieurs commentaires !
    
    /**
     * @ORM\OneToMany(targetEntity="Sdz\Bundle\BlogBundle\Entity\ArticleCompetence", mappedBy="article")
     */
    private $articleCompentences;
    
    /**
     * @var \DateTime
     *
     @ORM\Column(type="date", nullable=true)
     */
    private $dateEdition;
 
    
     /**
     * @var \integer
     * @ORM\Column(name="nbCommentaires", type="integer")
     */
    private $nbCommentaires;
    
   /**
   * @Gedmo\Slug(fields={"titre","auteur"})
   * @ORM\Column(length=128, unique=true)
   */
  private $slug;
    
    
    
    public function __construct()
    {
        $this->date     = new \Datetime;
        $this->publication  = false;
        $this->nbCommentaires  = 0;
        $this->categories   = new \Doctrine\Common\Collections\ArrayCollection();
        $this->commentaires = new \Doctrine\Common\Collections\ArrayCollection();

    }
     
    
    
    /**
     * Get id
     *
     * @return integer 
     */    
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Article
     */
    public function setDate(\Datetime $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return Article
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set auteur
     *
     * @param string $auteur
     * @return Article
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get auteur
     *
     * @return string 
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     * @return Article
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string 
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set publication
     *
     * @param boolean $publication
     * @return Article
     */
    public function setPublication($publication)
    {
        $this->publication = $publication;

        return $this;
    }

    /**
     * Get publication
     *
     * @return boolean 
     */
    public function getPublication()
    {
        return $this->publication;
    }




    /**
     * Set image
     *
     * @param \Sdz\Bundle\BlogBundle\Entity\Image $image
     * @return Article
     */
    public function setImage(\Sdz\Bundle\BlogBundle\Entity\Image $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Sdz\Bundle\BlogBundle\Entity\Image 
     */
    public function getImage()
    {
        return $this->image;
    }



    /**
     * Add categories
     *
     * @param \Sdz\Bundle\BlogBundle\Entity\Categorie $categories
     * @return Article
     */
    public function addCategory(\Sdz\Bundle\BlogBundle\Entity\Categorie $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Sdz\Bundle\BlogBundle\Entity\Categorie $categories
     */
    public function removeCategory(\Sdz\Bundle\BlogBundle\Entity\Categorie $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add commentaires
     *
     * @param \Sdz\Bundle\BlogBundle\Entity\Commentaire $commentaires
     * @return Article
     */
    public function addCommentaire(\Sdz\Bundle\BlogBundle\Entity\Commentaire $commentaires)
    {
        $this->commentaires[] = $commentaires;
        $commentaires->setArticle($this); // On ajoute ceci
        return $this;
    }

    /**
     * Remove commentaires
     *
     * @param \Sdz\Bundle\BlogBundle\Entity\Commentaire $commentaires
     */
    public function removeCommentaire(\Sdz\Bundle\BlogBundle\Entity\Commentaire $commentaires)
    {
        $this->commentaires->removeElement($commentaires);
    }

    /**
     * Get commentaires
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }
    
    
      /**
     * Set dateEdition
     *
     * @param \DateTime $dateEdition
     * @return Article
     */
      public function setDateEdition(\Datetime $dateEdition)
    {
        $this->dateEdition = $dateEdition;

        return $this;
    }

    /**
     * Get dateEdition
     *
     * @return \DateTime 
     */
    public function getDateEdition()
    {
        return $this->date;
    }

    /**
    * @ORM\PreUpdate
    */
    public function updateDate()
  {
    $this->setDateEdition(new \Datetime());
  }
    

    /**
     * Set nbCommentaires
     *
     * @param integer $nbCommentaires
     * @return Article
     */
    public function setNbCommentaires($nbCommentaires)
    {
        $this->nbCommentaires = $nbCommentaires;

        return $this;
    }

    /**
     * Get nbCommentaires
     *
     * @return integer 
     */
    public function getNbCommentaires()
    {
        return $this->nbCommentaires;
    }
    


    /**
     * Set slug
     *
     * @param string $slug
     * @return Article
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add articleCompentences
     *
     * @param \Sdz\Bundle\BlogBundle\Entity\ArticleCompetence $articleCompentences
     * @return Article
     */
    public function addArticleCompentence(\Sdz\Bundle\BlogBundle\Entity\ArticleCompetence $articleCompentences)
    {
        $this->articleCompentences[] = $articleCompentences;

        return $this;
    }

    /**
     * Remove articleCompentences
     *
     * @param \Sdz\Bundle\BlogBundle\Entity\ArticleCompetence $articleCompentences
     */
    public function removeArticleCompentence(\Sdz\Bundle\BlogBundle\Entity\ArticleCompetence $articleCompentences)
    {
        $this->articleCompentences->removeElement($articleCompentences);
    }

    /**
     * Get articleCompentences
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArticleCompentences()
    {
        return $this->articleCompentences;
    }
}

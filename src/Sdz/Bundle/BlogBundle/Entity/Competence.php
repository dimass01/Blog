<?php

namespace Sdz\Bundle\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Competence
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Competence
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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="Sdz\Bundle\BlogBundle\Entity\ArticleCompetence", mappedBy="competence")
     */
    private $articleCompentences;
    
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
     * Set nom
     *
     * @param string $nom
     * @return Competence
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articleCompentences = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add articleCompentences
     *
     * @param \Sdz\Bundle\BlogBundle\Entity\ArticleCompetence $articleCompentences
     * @return Competence
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

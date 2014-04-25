<?php
// src/Sdz/Bundle\BlogBundle/Entity/ArticleCompetence.php
namespace Sdz\Bundle\BlogBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 */
class ArticleCompetence
{
  /**
    * @ORM\Id
    * @ORM\ManyToOne(targetEntity="Sdz\Bundle\BlogBundle\Entity\Article", inversedBy="articleCompentences")
    */
  private $article;
  /**
    * @ORM\Id
    * @ORM\ManyToOne(targetEntity="Sdz\Bundle\BlogBundle\Entity\Competence", inversedBy="articleCompentences")
    */
  private $competence;
  /**
    * @ORM\Column()
    */
  private $niveau; // Ici j'ai un attribut de relation « niveau »
  // … les autres attributs
  // Getter et setter pour l'entité Article
  

    /**
     * Set niveau
     *
     * @param string $niveau
     * @return ArticleCompetence
     */
    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * Get niveau
     *
     * @return string 
     */
    public function getNiveau()
    {
        return $this->niveau;
    }

    /**
     * Set article
     *
     * @param \Sdz\Bundle\BlogBundle\Entity\Article $article
     * @return ArticleCompetence
     */
    public function setArticle(\Sdz\Bundle\BlogBundle\Entity\Article $article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return \Sdz\Bundle\BlogBundle\Entity\Article 
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set competence
     *
     * @param \Sdz\Bundle\BlogBundle\Entity\Competence $competence
     * @return ArticleCompetence
     */
    public function setCompetence(\Sdz\Bundle\BlogBundle\Entity\Competence $competence)
    {
        $this->competence = $competence;

        return $this;
    }

    /**
     * Get competence
     *
     * @return \Sdz\Bundle\BlogBundle\Entity\Competence 
     */
    public function getCompetence()
    {
        return $this->competence;
    }
}

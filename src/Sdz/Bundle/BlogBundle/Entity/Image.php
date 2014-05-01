<?php

namespace Sdz\Bundle\BlogBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Image
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Sdz\Bundle\BlogBundle\Entity\ImageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Image
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
     * @ORM\Column(name="extension", type="string", length=255)
     */
    private $extension;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;

    private $file;

    private $tempFilename;
    
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
     * Set extension
     *
     * @param string $extension
     * @return Image
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string 
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set alt
     *
     * @param string $alt
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string 
     */
    public function getAlt()
    {
        return $this->alt;
    }
    
    
    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
    $this->file = $file;
    // On vérifie si on avait déjà un fichier pour cette entité
    if (null !== $this->extension) {
      // On sauvegarde l'extension du fichier pour le supprimer plus tard
      $this->tempFilename = $this->extension;
      // On réinitialise les valeurs des attributs url et alt
      $this->extension = null;
      $this->alt = null;
    }
        
    }
    
    
    public function getUrl()
    {
        return $this->getUploadDir()."/".$this->id.".".$this->extension;
    }

    /**
    * @ORM\PrePersist
    * @ORM\PreUpdate
    */
   public function preUpload()
   {
    // Si jamais il n'y a pas de fichier (champ facultatif)
    if (null === $this->file) {
      return;
    }
    // On sauvegarde le nom de fichier dans notre attribut $extension
    $this->extension = $this->file->guessClientExtension();
    // On crée également le futur attribut alt de notre balise <img>
    $this->alt = $this->file->getClientOriginalName();
   }

   
 /**
    * @ORM\PostPersist
    * @ORM\PostUpdate
    */
   public function upload()
   {
        if (null === $this->file) {
            return;
        }
        
        if (null!== $this->tempFilename) {

            $oldFile = $this->getUploadRootDir().'/'.$this->id.'.'.$this->tempFilename;
            if (file_exists($oldFile)) {
              unlink($oldFile);
            }
           
        }
        // On déplace le fichier envoyé dans le répertoire de notre choix
        $this->file->move($this->getUploadRootDir(),$this->id.".".$this->extension);
  
    }
    
    /**
    * @ORM\PreRemove
    */   
    public function preRemoveUpload()
    {   
        $this->tempFilename = $this->getUploadRootDir()."/".$this->id.".".$this->extension;
        
    }
      /**
    * @ORM\PostRemove
    */   
    public function postRemoveUpload()
    {  
        if (file_exists($this->tempFilename)) {
      // On supprime le fichier
             unlink($this->tempFilename);
        }
        
    }  
    
  public function getUploadDir()
  {
    // On retourne le chemin relatif vers l'image pour un navigateur
    return 'uploads/img';
  }
  protected function getUploadRootDir()
  {
    // On retourne le chemin relatif vers l'image pour notre code PHP
    return __DIR__.'/../../../../../web/'.$this->getUploadDir();
  }


}

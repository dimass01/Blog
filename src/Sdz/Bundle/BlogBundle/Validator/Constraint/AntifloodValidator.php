<?php

namespace Sdz\Bundle\BlogBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
class AntiFloodValidator extends ConstraintValidator
{
    
  private $request;
  private $em;  
    
   public function __construct(Request $request, EntityManager $em)
  {
    $this->request = $request;
    $this->em      = $em;
  }
  
  public function validate($value, Constraint $constraint)
  {
    // On récupère l'IP de celui qui poste
    $ip = $this->request->server->get('REMOTE_ADDR');
    // On vérifie si cette IP a déjà posté un message il y a moins de 15 secondes
    /*$isFlood = $this->em->getRepository('SdzBlogBundle:Commentaire')
                        ->isFlood($ip, 15); // Bien entendu, il faudrait écrire cette méthode isFlood, c'est pour l'exemple
    if (strlen($value) < 3 && $isFlood) {
      // C'est cette ligne qui déclenche l'erreur pour le formulaire, avec en argument le message
      $this->context->addViolation($constraint->message,array("%string%"=>$value));
    }
     * 
     */
    if ($ip =="zaza") {
         $this->context->addViolation($constraint->message,array("%string%"=>$value));
    
     }
  }
}
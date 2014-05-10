<?php
// src/Sdz/UserBundle/Controller/SecurityController.php;
namespace Sdz\Bundle\UserBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
class SecurityController extends Controller

{
  public function loginAction(Request $request)
  {
    // Si le visiteur est déjà identifié, on le redirige vers l'accueil
      // ne sert a rien car dans le parefeu login, il n y a pas d authentification, donc pas de role.
      // En effet le parefeu login n'appartient pas au parefeu main 
    if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
      return $this->redirect($this->generateUrl('homepage'));
    }
  
    $session = $request->getSession();
    // On vérifie s'il y a des erreurs d'une précédente soumission du formulaire
    if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
      $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
    } else {
      $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
      $session->remove(SecurityContext::AUTHENTICATION_ERROR);
    }
    return $this->render('SdzUserBundle:Security:login.html.twig', array(
      // Valeur du précédent nom d'utilisateur entré par l'internaute
      'last_username' => $session->get(SecurityContext::LAST_USERNAME),
      'error'         => $error,
    ));
  }
}
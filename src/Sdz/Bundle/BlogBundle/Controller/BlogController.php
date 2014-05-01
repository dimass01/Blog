<?php

namespace Sdz\Bundle\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
Use Sdz\Bundle\BlogBundle\Entity\Article;
Use Sdz\Bundle\BlogBundle\Entity\Image;
Use Sdz\Bundle\BlogBundle\Entity\Commentaire;
Use Sdz\Bundle\BlogBundle\Form\ArticleType;
Use Sdz\Bundle\BlogBundle\Form\ArticleEditType;
class BlogController extends Controller
{
    /**
     * @Route(
     *  path="/{page}",
     *  name="sdzblog_accueil",
     *  requirements = { 
     *   "page" = "\d*"
     *  },
     *  defaults={"page" = "1"}
     * )
     * @Template()
     */
    public function indexAction($page)
    {
        
        $nombreArticlesParPage=$this->container->getParameter('blog')['nombreArticlesParPage'];
        
        $text="eaeaeae";
        // On récupère le service
        $antispam = $this->container->get('sdz_blog.antispam');

        // Je pars du principe que $text contient le texte d'un message quelconque
        if ($antispam->isSpam($text)) {
          throw new \Exception('Votre message a été détecté comme spam !');
        }


        // Le message n'est pas un spam, on continue l'action…     
        $articles= $this->getDoctrine()->getManager()->getRepository('SdzBlogBundle:Article')->getArticles($nombreArticlesParPage,$page);   

       
        // Ici, on récupérera la liste des articles, puis on la passera au template
        // Mais pour l'instant, on ne fait qu'appeler le template
        return array('articles' => $articles,
                     'page' => $page,
                     'nombrePages' => ceil(count($articles)/$nombreArticlesParPage),
                
                );

    }
    

     /**
     * @Route("/article/{slug}", name="sdzblog_voir")
     * @Template()
     */
  

 public function voirAction(Article $article)
  {
    // On récupère l'EntityManager

    // On récupère les articleCompetence pour l'article $article
    $liste_articleCompetence = $this->get('doctrine')->getManager()->getRepository('SdzBlogBundle:ArticleCompetence')
                            ->findByArticle($article->getId());

    // Puis modifiez la ligne du render comme ceci, pour prendre en compte les articleCompetence :
    return $this->render('SdzBlogBundle:Blog:voir.html.twig', array(
      'article'			 => $article,
      'liste_articleCompetence'	 => $liste_articleCompetence,
      // … et évidemment les autres variables que vous pouvez avoir
    ));
  }
    
     /**
     * @Route("/ajouter", name="sdzblog_ajouter")
     * @Template()
     */

  public function ajouterAction(Request $request)
  {

  $article = new Article;
  $form = $this->createForm(new ArticleType(array("Symfony2","Évènement")), $article);


  if ($request->getMethod() == 'POST') {
    $form->bind($request);
    

    if ($form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($article);
      $em->flush();

      return $this->redirect($this->generateUrl('sdzblog_accueil'));
    }
  }

  return $this->render('SdzBlogBundle:Blog:ajouter.html.twig', array(
    'form' => $form->createView(),
  ));
}
  
    
      /**
     * @Route("/modifier/{id}", name="sdzblog_modifier",requirements = {"id" = "\d+"})
     * @Template()
     */
    public function modifierAction(Article $article,Request $request)
    {
     if ($article == null) {
      throw $this->createNotFoundException('Article[id='.$article->getId().'] inexistant');
    }
    
  $form = $this->createForm(new ArticleEditType(array("Symfony2","Évènement")), $article);


  if ($request->getMethod() == 'POST') {
    $form->bind($request);
    

    if ($form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($article);
      $em->flush();

      return $this->redirect($this->generateUrl('sdzblog_accueil'));
    }
  }

  // Ici, on s'occupera de la création et de la gestion du formulaire
        return $this->render('SdzBlogBundle:Blog:modifier.html.twig', array(
           'form' => $form->createView(),
           'article' => $article
        ));

    
    }  
    
     /**
     * @Route("/supprimer/{id}", name="sdzblog_supprimer", requirements = {"id" = "\d+"})
     * @Template()
     */
    public function supprimerAction(Article $article, Request $request)
    {
   
    if ($article == null) {
      throw $this->createNotFoundException('Article[id='.$article->getId().'] inexistant');
    }
    
    // On crée un formulaire vide, qui ne contiendra que le champ CSRF
    // Cela permet de protéger la suppression d'article contre cette faille
    $form = $this->createFormBuilder()->getForm();
    
    
    if ($request->isMethod('POST')){
    $form->bind($request);

         if ($form->isValid()) {

           // On supprime l'article
           $em = $this->getDoctrine()->getManager();
           $em->remove($article);
           $em->flush();
       
       $request->getSession()->getFlashBag()->add('info', 'Article bien supprimé');
      // Puis on redirige vers l'accueil
      return $this->redirect( $this->generateUrl('sdzblog_accueil') );
    }
    
   }

    // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
    return $this->render('SdzBlogBundle:Blog:supprimer.html.twig', array(
      'article' => $article,
      'form'    => $form->createView()
    ));
    }  
    
      /**
     * @Route(
       *  path="/{annee}/{slug}.{format}",
       *  name="sdzblog_voir_slug",
       *  requirements = { 
       *   "annee" = "\d{4}",
       *   "format"= "html|xml" 
       *  },
       *  defaults={"format" = "html"}
       * )
     * @Template()
     */
    public function voirSlugAction($slug,$annee,$format)
    {
     return new Response("On pourrait afficher l'article correspondant au slug '".$slug."', créé en ".$annee." et au format ".$format.".");
    }     
   
    
       /**
     * @Route(
       *  path="/menu",
       *  name="sdzblog_menu",
       *  defaults={"nombre" = "3"}
       * )
     * @Template()
     */   
    public function menuAction($nombre) // Ici, nouvel argument $nombre, on l'a transmis via le render() depuis la vue
  {
    // On fixe en dur une liste ici, bien entendu par la suite on la récupérera depuis la BDD !
    // On pourra récupérer $nombre articles depuis la BDD,
    // avec $nombre un paramètre qu'on peut changer lorsqu'on appelle cette action
    $liste =$this->get('doctrine')
               ->getManager()
               ->getRepository('SdzBlogBundle:Article')
               ->findBy(
                    array(),          // Pas de critère
                    array('date' => 'desc'), // On trie par date décroissante
                    $nombre,         // On sélectionne $nombre articles
                    0                // À partir du premier
                       );
    
    return array(
      'liste_articles' => $liste // C'est ici tout l'intérêt : le contrôleur passe les variables nécessaires au template !
    );
  }
  
/**
* @Route(
  *  path="/modifierimage/{id_article}",
  *  name="sdzblog_modifier_image"
  * )
* @Template()
*/   
  public function modifierImageAction($id_article)
  {
    $em = $this->getDoctrine()->getManager();
    // On récupère l'article
    $article = $em->getRepository('SdzBlogBundle:Article')->find($id_article);
    // On modifie l'URL de l'image par exemple
    $article->getImage()->setUrl('test.png');
    
    // On n'a pas besoin de persister notre article (si vous le faites, aucune erreur n'est déclenchée, Doctrine l'ignore)
    // Rappelez-vous, il l'est automatiquement car on l'a récupéré depuis Doctrine
    // Pas non plus besoin de persister l'image ici, car elle est également récupérée par Doctrine
    // On déclenche la modification
    $em->flush();
    return new Response('OK');
  }
  
  /**
* @Route(
  *  path="/test",
  *  name="sdzblog_test"
  * )
* @Template()
*/   
  public function testRequestAction()
  {
    $em = $this->getDoctrine()->getManager();
    // On récupère l'article
    $coms = $em->getRepository('SdzBlogBundle:Article')->testRequest();
    
       echo  $coms;
       
 
    return new Response("ok");
  }
  
  
    /**
    * @Route(
      *  path="/image_article/{id_article}",
      *  name="sdzblog_image"
      * )
    * @Template()
    */   
    public function getImageArticleAction($id_article)
  {
    $em = $this->getDoctrine()->getManager();
    // On récupère l'article
    $article = $em->getRepository('SdzBlogBundle:Article')->find($id_article);
    // On modifie l'URL de l'image par exemple
    return  new Response($article->getImage()->getUrl());


  }
  
      /**
    * @Route(
      *  path="/ajouter_image_article/{id_article}/{url_image}",
      *  name="sdzblog_image_ajouter"
      * )
    * @Template()
    */   
  public function addImageArticleAction($id_article,$url_image)
  {
    $em = $this->getDoctrine()->getManager();
    // On récupère l'article
    $article = $em->getRepository('SdzBlogBundle:Article')->find($id_article);
    $image= new Image();
    $image->setUrl($url_image);
    $image->setAlt($url_image);
    $article->setImage($image);
    $em->flush();
    // On modifie l'URL de l'image par exemple
    return  new Response("OK !nouvel ID image crée= ".$image->getId());


  }
   /**
    * @Route(
    *  path="/liste_test",
    *  name="sdzblog_test"
    * )
    * @Template()
   */   
  public function listeTestAction()

{

  $listeArticles = $this->getDoctrine()

                        ->getManager()

                        ->getRepository('SdzBlogBundle:Article')
                        ->getArticleAvecCommentaires();

 
  
  return array("articles"=>$listeArticles);

  // …

}

   /**
    * @Route(
    *  path="/test",
    *  name="sdzblog_testaction"
    * )
    * @Template()
   */   
public function testAction()
{
  $article = new Article();
  $article->setTitre("L'histoire d'un bon weekend !");
  $article->setAuteur("eae'ezez");
 $commentaire1 = new Commentaire();
    $commentaire1->setAuteur('winzou');
    $commentaire1->setContenu('On veut les photos !');
     $article->addCommentaire($commentaire1);  
    
  $em = $this->getDoctrine()->getManager();
  echo "avant persist ".$article->getNbCommentaires();
   $em->persist($commentaire1);
    echo "apres persist ".$article->getNbCommentaires();
   $em->persist($article);
   echo "avant flush ".$article->getNbCommentaires();
  $em->flush(); // C'est à ce moment qu'est généré le slug
 echo "apres flush ".$article->getNbCommentaires();
  return new Response('Slug généré : '.$article->getSlug()); // Affiche « Slug généré : l-histoire-d-un-bon-weekend »
}

  }



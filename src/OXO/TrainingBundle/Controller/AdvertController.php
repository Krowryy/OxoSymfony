<?php

namespace OXO\TrainingBundle\Controller;

// N'oubliez pas ce use :
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use OXO\TrainingBundle\Entity\Category;
use OXO\TrainingBundle\Entity\Advert;
use OXO\TrainingBundle\Entity\Application;
use OXO\TrainingBundle\Entity\Image;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OXO\TrainingBundle\Entity\AdvertSkill;



class AdvertController extends Controller
{
  public function indexAction()
    {
      $listAdverts = array(
        array(
          'title'   => 'Recherche développpeur Symfony',
          'id'      => 1,
          'author'  => 'Alexandre',
          'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
          'date'    => new \Datetime()),
        array(
          'title'   => 'Mission de webmaster',
          'id'      => 2,
          'author'  => 'Hugo',
          'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
          'date'    => new \Datetime()),
        array(
          'title'   => 'Offre de stage webdesigner',
          'id'      => 3,
          'author'  => 'Mathieu',
          'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
          'date'    => new \Datetime())
      );

      // Et modifiez le 2nd argument pour injecter notre liste
      return $this->render('OXOTrainingBundle:Advert:index.html.twig', array(
        'listAdverts' => $listAdverts
      ));
    }

  public function viewSlugAction($slug, $year, $format)
   {
       return new Response("On pourrait afficher
       l'annonce correspondant au slug '".$slug."', créée en ".$year." et au format ".$format.".");
   }

   public function addAction(Request $request)
   {
     // On récupère l'EntityManager
   $em = $this->getDoctrine()->getManager();

   // Création de l'entité Advert
   $advert = new Advert();
   $advert->setTitle('Recherche développeur Symfony.');
   $advert->setAuthor('Alexandre');
   $advert->setContent("Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…");
   $advert->setDate(new \DateTime());

   // On récupère toutes les compétences possibles
   $listSkills = $em->getRepository('OXOTrainingBundle:Skill')->findAll();

   // Pour chaque compétence
   foreach ($listSkills as $skill) {
     // On crée une nouvelle « relation entre 1 annonce et 1 compétence »
     $advertSkill = new AdvertSkill();

     // On la lie à l'annonce, qui est ici toujours la même
     $advertSkill->setAdvert($advert);
     // On la lie à la compétence, qui change ici dans la boucle foreach
     $advertSkill->setSkill($skill);

     // Arbitrairement, on dit que chaque compétence est requise au niveau 'Expert'
     $advertSkill->setLevel('Expert');

     // Et bien sûr, on persiste cette entité de relation, propriétaire des deux autres relations
     $em->persist($advertSkill);
   }

   // Doctrine ne connait pas encore l'entité $advert. Si vous n'avez pas défini la relation AdvertSkill
   // avec un cascade persist (ce qui est le cas si vous avez utilisé mon code), alors on doit persister $advert
   $em->persist($advert);

   // On déclenche l'enregistrement
   $em->flush();

   // … reste de la méthode


     // Reste de la méthode qu'on avait déjà écrit
     if ($request->isMethod('POST')) {
       $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

       // Puis on redirige vers la page de visualisation de cettte annonce
       return $this->redirectToRoute('oxo_platform_view', array('id' => $advert->getId()));
     }

     // Si on n'est pas en POST, alors on affiche le formulaire
     return $this->render('OXOTrainingBundle:Advert:add.html.twig', array('advert' => $advert));
   }

   public function editAction($id, Request $request)
   {
     // ...
    $em = $this->getDoctrine()->getManager();

    // On récupère l'annonce $id
    $advert = $em->getRepository('OXOTrainingBundle:Advert')->find($id);

    if (null === $advert) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }

    // La méthode findAll retourne toutes les catégories de la base de données
    $listCategories = $em->getRepository('OXOTrainingBundle:Category')->findAll();

    // On boucle sur les catégories pour les lier à l'annonce
    foreach ($listCategories as $category) {
      $advert->addCategory($category);
    }

    // Pour persister le changement dans la relation, il faut persister l'entité propriétaire
    // Ici, Advert est le propriétaire, donc inutile de la persister car on l'a récupérée depuis Doctrine

    // Étape 2 : On déclenche l'enregistrement
    $em->flush();

     return $this->render('OXOTrainingBundle:Advert:edit.html.twig', array(
       'advert' => $advert
     ));
   }

      public function deleteAction($id)
      {
        $em = $this->getDoctrine()->getManager();

        // On récupère l'annonce $id
        $advert = $em->getRepository('OXOTrainingBundle:Advert')->find($id);

        if (null === $advert) {
          throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        // On boucle sur les catégories de l'annonce pour les supprimer
        foreach ($advert->getCategories() as $category) {
          $advert->removeCategory($category);
        }

        // Pour persister le changement dans la relation, il faut persister l'entité propriétaire
        // Ici, Advert est le propriétaire, donc inutile de la persister car on l'a récupérée depuis Doctrine

        // On déclenche la modification
        $em->flush();

        // ...
            // Ici, on récupérera l'annonce correspondant à $id

        // Ici, on gérera la suppression de l'annonce en question

        return $this->render('OXOTrainingBundle:Advert:delete.html.twig');
      }

      public function menuAction()
      {
        // On fixe en dur une liste ici, bien entendu par la suite
        // on la récupérera depuis la BDD !
        $listAdverts = array(
          array('id' => 2, 'title' => 'Recherche développeur Symfony'),
          array('id' => 5, 'title' => 'Mission de webmaster'),
          array('id' => 9, 'title' => 'Offre de stage webdesigner')
        );

        return $this->render('OXOTrainingBundle:Advert:menu.html.twig', array(
          // Tout l'intérêt est ici : le contrôleur passe
          // les variables nécessaires au template !
          'listAdverts' => $listAdverts
        ));
      }

      public function viewAction($id)
      {
        $em = $this->getDoctrine()->getManager();

        // On récupère l'annonce $id
        $advert = $em
          ->getRepository('OXOTrainingBundle:Advert')
          ->find($id)
        ;

        if (null === $advert) {
          throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        // On avait déjà récupéré la liste des candidatures
        $listApplications = $em
          ->getRepository('OXOTrainingBundle:Application')
          ->findBy(array('advert' => $advert))
        ;

        // On récupère maintenant la liste des AdvertSkill
        $listAdvertSkills = $em
          ->getRepository('OXOTrainingBundle:AdvertSkill')
          ->findBy(array('advert' => $advert))
        ;

        return $this->render('OXOTrainingBundle:Advert:view.html.twig', array(
          'advert'           => $advert,
          'listApplications' => $listApplications,
          'listAdvertSkills' => $listAdvertSkills
        ));
      }

      public function testAction()
      {
        $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('OXOTrainingBundle:Advert')
        ;

        $listAdverts = $repository->myFindAll();

        // ...
      }
}

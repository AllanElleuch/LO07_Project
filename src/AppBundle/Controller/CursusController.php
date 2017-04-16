<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Cursus;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CursusController extends Controller
{
    /**
     * affiche les cursus d'un étudiant
     * @Route("/", name="homepage")
     * @Route("/cursus/mes-cursus/")
     */
    public function mesCursusAction(Request $request)
    {

      $cursus = $this->getDoctrine()
       ->getRepository('AppBundle:Cursus')
       ->findAll();

/*
        $cursus = array(
            array(
                'id' => 1,
                'label' => 'Mon cursus',
                'student' => 'Corentin Laithier - 36795',
                'nbElements' => '3',
            ),
            array(
                'id' => 2,
                'label' => 'Parcours UTT',
                'student' => 'Allan Elleuch - 39678',
                'nbElements' => '5',
            ),
            array(
                'id' => 3,
                'label' => 'Parcours Test',
                'student' => 'Corentin Laithier - 36795',
                'nbElements' => '6',
            ),
        );
*/

        return $this->render('cursus/mes-cursus.html.twig', array(
            'nav' => "cursus",
            'subnav' => 'mes-cursus','cursus'=>$cursus,
        ));

  }




  /**
   * @Route("/cursus/delete/{id}")
   */  public function deleteCursus(Cursus $cursus)
 {

   if (!$cursus) {
    throw $this->createNotFoundException('Cursus introuvable');
}
$em = $this->getDoctrine()->getEntityManager();

$em->remove($cursus);
$em->flush();
return $this->redirectToRoute('homepage');


}

/**
 * @Route("/cursus/update/{id}")
 */  public function updateCursus(Request $request,$id)
{



  // create a cursus and give it some dummy data for this example
  $cursus = $this->getDoctrine()
   ->getRepository('AppBundle:Cursus')
   ->find($id);

   if (!$cursus) {
        throw $this->createNotFoundException('Aucun cursus à édité.');
    }


   $form = $this->createFormBuilder($cursus)
       ->add('label', TextType::class, array('label' => 'Nom du cursus','attr' => array('placeholder'=>'ISI/SRT Semestre X Branche Y','class'=>'form-control')))
       ->add('envoyer', SubmitType::class, array('label' => 'Modifier cursus'))
       ->getForm();



  $form->handleRequest($request);

  if ($form->isSubmitted() && $form->isValid()) {
      // $form->getData() holds the submitted values
      // but, the original `$task` variable has also been updated
          $cursus = $form->getData();

      // ... perform some action, such as saving the task to the database
      // for example, if Task is a Doctrine entity, save it!
      $em = $this->getDoctrine()->getManager();
      $em->persist($cursus);
      $em->flush();

    return $this->redirectToRoute('homepage');


}

return $this->render('cursus/new.html.twig', array(
    'form' => $form->createView(),
    'nav' => "cursus",
    'subnav' => "new",
));


}


/**
 * @Route("/cursus/duplicate/{id}")
 */  public function duplicateCursus(Request $request,$id)
{



  // create a cursus and give it some dummy data for this example
  $cursus = $this->getDoctrine()
   ->getRepository('AppBundle:Cursus')
   ->find($id);

    $em = $this->getDoctrine()->getManager();
    $new = clone $cursus;
    $em->persist($new);
    $em->flush();



    return $this->redirectToRoute('homepage');




}



    /**
     * @Route("/cursus/new/")
     */
    public function newCursusAction(Request $request)
    {

      // create a cursus and give it some dummy data for this example
      $cursus = new Cursus();
      //$cursus->setLabel('Mon cursus de ...');

      $form = $this->createFormBuilder($cursus)
          ->add('label', TextType::class, array('label' => 'Nom du cursus','attr' => array('placeholder'=>'ISI/SRT Semestre X Branche Y','class'=>'form-control')))
          ->add('label', TextType::class, array('label' => 'Nom du cursus','attr' => array('placeholder'=>'ISI/SRT Semestre X Branche Y','class'=>'form-control')))
          ->add('envoyer', SubmitType::class, array('label' => 'Créer un cursus'))
          ->getForm();

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
          // $form->getData() holds the submitted values
          // but, the original `$task` variable has also been updated
              $cursus = $form->getData();

          // ... perform some action, such as saving the task to the database
          // for example, if Task is a Doctrine entity, save it!
          $em = $this->getDoctrine()->getManager();
          $em->persist($cursus);
          $em->flush();

        return $this->redirectToRoute('homepage');
    }



      return $this->render('cursus/new.html.twig', array(
          'form' => $form->createView(),
          'nav' => "cursus",
          'subnav' => "new",
      ));



    }





    /**
     * @Route("/cursus/import/")
     */
    public function importCursusAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('cursus/import.html.twig', array(
            'nav' => "cursus",
            'subnav' => "import",
        ));
    }


    /**
     * @Route("/cursus/export/")
     */
    public function exportCursusAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('cursus/export.html.twig', array(
            'nav' => "cursus",
            'subnav' => "export",
        ));
    }
}

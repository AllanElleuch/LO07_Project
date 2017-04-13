<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Cursus;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Route("/cursus/mes-cursus/")
     */
    public function mesCursusAction(Request $request)
    {

      $cursus = $this->getDoctrine()
       ->getRepository('AppBundle:Cursus')
       ->findAll();
/*
   if (!$cursus) {
       throw $this->createNotFoundException(
           'Pas de cursus trouver '
       );
}
*/

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

        // replace this example code with whatever you need
        return $this->render('cursus/mes-cursus.html.twig', array(
            'currentPage' => 'mes-cursus','cursus'=>$cursus,
            'cursus' => $cursus,
        ));

  }


    /**
     * @Route("/cursus/new/")
     */
    public function newCursusAction(Request $request)
    {

      // create a cursus and give it some dummy data for this example
      $cursus = new Cursus();
      $cursus->setLabel('Mon cursus de ...');

      $form = $this->createFormBuilder($cursus)
          ->add('label', TextType::class)
          ->add('envoyer', SubmitType::class, array('label' => 'création cursus'))
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
          'form' => $form->createView(),'currentPage' => "new",
      ));



      /*  // replace this example code with whatever you need
        return $this->render('cursus/new.html.twig', array(
            'currentPage' => "new",
        ));
        */
    }


    /**
     * @Route("/cursus/import/")
     */
    public function importCursusAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('cursus/import.html.twig', array(
            'currentPage' => "import",
        ));
    }


    /**
     * @Route("/cursus/export/")
     */
    public function exportCursusAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('cursus/export.html.twig', array(
            'currentPage' => "export",
        ));
    }
}

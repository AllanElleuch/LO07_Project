<?php
/**
 * Created by PhpStorm.
 * User: corentinlaithier
 * Date: 04/05/2017
 * Time: 08:38
 */

namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Etudiants;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EtudiantsController extends Controller{
    /**
     * affiche les cursus d'un étudiant
     * @Route("/etudiants/etudiants/")
     */
    public function viewStudentsAction(Request $request) {

        $students = $this->getDoctrine()
                    ->getRepository('AppBundle:Etudiants')
                    ->findAll();

        return $this->render('etudiants/etudiants.html.twig', array(
            'nav' => "etudiants",
            'subnav' => 'etudiants',
            'etudiants' => $students
        ));

    }

    /**
     * affiche les cursus d'un étudiant
     * @Route("/etudiants/new/")
     */
    public function newStudentAction(Request $request) {
      $etudiants = new Etudiants();

      $form = $this->createFormBuilder($etudiants)
          ->add('nom', TextType::class, array('label' => 'Prenom', 'attr' => array('placeholder' => 'Prénom de l\'étudiant', 'class' => 'form-control')))
          ->add('prenom', TextType::class, array('label' => 'Nom', 'attr' => array('placeholder' => 'Nom de l\'étudiant', 'class' => 'form-control')))
          ->add('numEtu', IntegerType::class, array('label' => 'Numéro', 'attr' => array('placeholder' => 'Numéro   de l\'étudiant', 'class' => 'form-control')))

          ->add('filieres', EntityType::class, array(
              'class' => 'AppBundle:Filieres',
              'choice_label' => 'label',
              'label' => 'Filiere'))

          ->add('admissions', EntityType::class, array(
              'class' => 'AppBundle:Admissions',
              'choice_label' => 'label',
              'label' => 'Admissions'))
              ->add('cursus', EntityType::class, array(
                  'class' => 'AppBundle:Cursus',
                  'choice_label' => 'label',
                  'label' => 'Cursus',
                  'required' => 'false'))

          ->add('envoyer', SubmitType::class, array('label' => 'Créer un étudiant'))

          ->getForm();

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
          // $form->getData() holds the submitted values
          // but, the original `$task` variable has also been updated
          $etudiants = $form->getData();



          // ... perform some action, such as saving the task to the database
          // for example, if Task is a Doctrine entity, save it!
          $em = $this->getDoctrine()->getManager();

          $em->persist($etudiants);
          $em->flush();

          return $this->redirectToRoute('homepage');
      }



        return $this->render('etudiants/new.html.twig', array(
            'nav' => "etudiants",
            'subnav' => 'new',
            'form' => $form->createView(),

        ));

    }
}

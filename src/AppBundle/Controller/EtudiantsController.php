<?php
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
     * viewStudentsAction
     * ==================
     * Affiche les cursus d'un étudiant
     * @Route("/etudiants/etudiants/", name="homestudents")
     */
    public function viewStudentsAction(Request $request) {
        /* Récupération de tous les étudiants dans la base de données */
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
     * newStudentAction
     * ================
     * Crée un nouvel étudiant
     * @Route("/etudiants/new/")
     */
    public function newStudentAction(Request $request) {
        $etudiants = new Etudiants();

        $form = $this->createFormBuilder($etudiants)
            ->add('prenom', TextType::class, array(
                'label' => 'Prenom',
                'attr' => array(
                    'placeholder' => 'Prénom de l\'étudiant',
                    'class' => 'form-control'
                )
            ))
            ->add('nom', TextType::class, array(
                'label' => 'Nom',
                'attr' => array(
                    'placeholder' => 'Nom de l\'étudiant',
                    'class' => 'form-control'
                )
            ))
            ->add('numEtu', IntegerType::class, array(
                'label' => 'Numéro',
                'attr' => array(
                    'placeholder' => 'Numéro de l\'étudiant',
                    'class' => 'form-control'
                )
            ))
            ->add('filieres', EntityType::class, array(
                'class' => 'AppBundle:Filieres',
                'choice_label' => 'label',
                'label' => 'Filiere'))

            ->add('admissions', EntityType::class, array(
                'class' => 'AppBundle:Admissions',
                'choice_label' => 'label',
                'label' => 'Admissions'))
            ->add('envoyer', SubmitType::class, array('label' => 'Créer un étudiant'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $etudiant = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($etudiant);
            $em->flush();

            return $this->redirectToRoute('homestudents');
        }

        return $this->render('etudiants/new.html.twig', array(
            'nav' => "etudiants",
            'subnav' => 'new',
            'form' => $form->createView(),
        ));
    }


    /**
     * updateStudentAction
     * ===================
     * Modification d'un étudiant
     * @Route("/etudiants/update/{id}")
     */
    public function updateStudentAction(Request $request, $id) {

        // create a cursus and give it some dummy data for this example
        $student = $this->getDoctrine()
            ->getRepository('AppBundle:Etudiants')
            ->find($id);

        if (!$student) {
            throw $this->createNotFoundException('Aucun étudiant à édité.');
        }


        $form = $this->createFormBuilder($student)
            ->add('prenom', TextType::class, array('label' => 'Prenom', 'attr' => array('placeholder' => 'Prénom de l\'étudiant', 'class' => 'form-control')))
            ->add('nom', TextType::class, array('label' => 'Nom', 'attr' => array('placeholder' => 'Nom de l\'étudiant', 'class' => 'form-control')))
            ->add('numEtu', IntegerType::class, array('label' => 'Numéro', 'attr' => array('placeholder' => 'Numéro de l\'étudiant', 'class' => 'form-control')))

            ->add('filieres', EntityType::class, array(
            'class' => 'AppBundle:Filieres',
            'choice_label' => 'label',
            'label' => 'Filiere')
            )

            ->add('admissions', EntityType::class, array(
            'class' => 'AppBundle:Admissions',
            'choice_label' => 'label',
            'label' => 'Admissions')
            )
            ->add('envoyer', SubmitType::class, array('label' => 'Modifier l\'étudiant'))
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $student = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($student);
            $em->flush();

            return $this->redirectToRoute('homestudents');
        }

        return $this->render('etudiants/new.html.twig', array(
            'nav' => "etudiants",
            'subnav' => 'new',
            'form' => $form->createView(),
            'etudiant' => $student,
        ));
    }

    /**
     * deleteStudentAction
     * ===================
     * Delete student and associated cursuses
     * @Route("/etudiants/delete/{id}")
     */
    public function deleteStudentAction(Request $request, $id) {
        /* Récupération de l'étudiant à supprimer */
        $student = $this->getDoctrine()
                    ->getRepository('AppBundle:Etudiants')
                    ->find($id);

        /* Levée d'une exception si l'étudiant n'existe pas */
        if (!$student) {
            throw $this->createNotFoundException('Étudiant introuvable');
        }

        /* Récupération de tous les cursus liés à l'étudiant à supprimer */
        $cursuses = $this->getDoctrine()
                    ->getRepository('AppBundle:Cursus')
                    ->findBy(array(
                        'etudiant' => $id,
                    ));

        /* Suppression des cursus et de l'étudiant */
        $em = $this->getDoctrine()->getEntityManager();
        foreach ($cursuses as $cursus) {
            $em->remove($cursus);
        }
        $em->remove($student);
        $em->flush();

        $students = $this->getDoctrine()
                    ->getRepository('AppBundle:Etudiants')
                    ->findAll();

        return $this->redirectToRoute('homestudents');

    }
}

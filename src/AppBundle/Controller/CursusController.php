<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Cursus;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class CursusController extends Controller {
    /**
     * affiche les cursus d'un étudiant
     * @Route("/", name="homepage")
     * @Route("/cursus/mes-cursus/")
     */
    public function mesCursusAction(Request $request) {

        $cursus = $this->getDoctrine()
            ->getRepository('AppBundle:Cursus')
            ->findAll();

        return $this->render('cursus/mes-cursus.html.twig', array(
            'nav' => "cursus",
            'subnav' => 'mes-cursus', 'cursus' => $cursus,
        ));

    }


    /**
     * @Route("/cursus/delete/{id}")
     */
    public function deleteCursus(Cursus $cursus) {

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
     */
    public function updateCursus(Request $request, $id) {


        // create a cursus and give it some dummy data for this example
        $cursus = $this->getDoctrine()
            ->getRepository('AppBundle:Cursus')
            ->find($id);

        if (!$cursus) {
            throw $this->createNotFoundException('Aucun cursus à édité.');
        }


        $form = $this->createFormBuilder($cursus)
            ->add('label', TextType::class, array('label' => 'Nom du cursus', 'attr' => array('placeholder' => 'ISI/SRT Semestre X Branche Y', 'class' => 'form-control')))
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
     */
    public function duplicateCursus(Request $request, $id) {


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
    public function newCursusAction(Request $request) {

        // create a cursus and give it some dummy data for this example
        $cursus = new Cursus();
        //$cursus->setLabel('Mon cursus de ...');

        $form = $this->createFormBuilder($cursus)
            ->add('label', TextType::class, array('label' => 'Nom du cursus', 'attr' => array('placeholder' => 'ISI/SRT Semestre X Branche Y', 'class' => 'form-control')))
            ->add('label', TextType::class, array('label' => 'Nom du cursus', 'attr' => array('placeholder' => 'ISI/SRT Semestre X Branche Y', 'class' => 'form-control')))
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
    public function importCursusAction(Request $request) {

        $form = $this->createFormBuilder()
            ->add('submitFile', FileType::class, array('label' => 'File to Submit'))
            ->add('envoyer', SubmitType::class, array('label' => 'Envoyer le fichier'))
            ->getForm();

        if ($request->getMethod('post') == 'POST') {
            // Bind request to the form
            $form->handleRequest($request);

            // If form is valid
            if ($form->isValid()) {
                // Get file
                $file = $form->get('submitFile');

                // Your csv file here when you hit submit button
                $file = $file->getData();

                print_r($file);
            }

        }

        // replace this example code with whatever you need
        return $this->render('cursus/import.html.twig', array(
            'nav' => "cursus",
            'subnav' => "import",
            'form' => $form->createView(),
        ));
    }


    /**
     * @Route("/cursus/export/{id}")
     */
    public function exportOneCursusAction(Request $request, $id) {

        // Exportation d'un cursus au format csv

        $cursus = $this->getDoctrine()
            ->getRepository('AppBundle:Cursus')
            ->find($id);


        return $this->redirectToRoute('exportCursusList');
    }

    /**
     * @Route("/cursus/export/",  name="exportCursusList")
     */
    public function exportCursusListAction(Request $request) {

        $cursus = $this->getDoctrine()
            ->getRepository('AppBundle:Cursus')
            ->findAll();


        // replace this example code with whatever you need
        return $this->render('cursus/export.html.twig', array(
            'nav' => "cursus",
            'subnav' => "export",
            'cursus' => $cursus,
        ));
    }


    /**
     * @Route("/phpinfo")
     */
    public function phpInfoAction(Request $request) {

        phpinfo();

    }


}
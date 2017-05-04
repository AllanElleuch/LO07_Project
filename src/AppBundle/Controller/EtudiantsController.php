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

        return $this->render('etudiants/new.html.twig', array(
            'nav' => "etudiants",
            'subnav' => 'new',
        ));

    }
}

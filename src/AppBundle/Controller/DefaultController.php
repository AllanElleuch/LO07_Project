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
     * affiche les cursus d'un étudiant
     * @Route("/", name="homepage")
     */
    public function homePageAction(Request $request) {

        $cursus = $this->getDoctrine()
            ->getRepository('AppBundle:Cursus')
            ->findAll();
        $reglements = $this->getDoctrine()
            ->getRepository('AppBundle:Reglement')
            ->findAll();
        $etudiants = $this->getDoctrine()
            ->getRepository('AppBundle:Etudiants')
            ->findAll();

        return $this->render('index.html.twig', array(
            'nav' => "",
            'cursus' => sizeof($cursus),
            'reglements' => sizeof($reglements),
            'etudiants' => sizeof($etudiants),
        ));

    }
}

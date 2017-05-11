<?php
/**
 * Created by PhpStorm.
 * User: corentinlaithier
 * Date: 13/04/2017
 * Time: 15:03
 */

namespace AppBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class ReglementsController extends Controller {

    /**
     * @Route("/reglements/mes-reglements/")
     */
    public function mesReglementsAction(Request $request)
    {
        $reglements = $this->getDoctrine()
            ->getRepository('AppBundle:Reglement')
            ->findAll();

        return $this->render('reglements/mes-reglements.html.twig', array(
            'nav' => "reglements",
            'subnav' => "mes-reglements",
            'reglements' => $reglements
        ));
    }
    /**
     * @Route("/reglements/import/")
     */
    public function importReglementsAction(Request $request)
    {
        return $this->render('reglements/import.html.twig', array(
            'nav' => "reglements",
            'subnav' => "import",
        ));
    }
    /**
     * @Route("/reglements/export/")
     */
    public function exportReglementsAction(Request $request)
    {
        return $this->render('reglements/export.html.twig', array(
            'nav' => "reglements",
            'subnav' => "export",
        ));
    }

}

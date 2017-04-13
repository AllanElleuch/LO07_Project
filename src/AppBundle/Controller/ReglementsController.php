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
        return $this->render('reglements/mes-reglements.html.twig', array(
            'currentPage' => "mes-reglements",
        ));
    }
    /**
     * @Route("/reglements/mes-reglements/")
     */
    public function importReglementsAction(Request $request)
    {
        return $this->render('reglements/import.html.twig', array(
            'currentPage' => "import",
        ));
    }
    /**
     * @Route("/reglements/mes-reglements/")
     */
    public function exportReglementsAction(Request $request)
    {
        return $this->render('reglements/export.html.twig', array(
            'currentPage' => "export",
        ));
    }

}
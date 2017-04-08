<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Route("/cursus/mes-cursus/")
     */
    public function mesCursusAction(Request $request)
    {

        $cursus = array(
            array(
                'label' => 'Mon cursus',
                'student' => 'Corentin Laithier - 36795',
                'nbElements' => '3',
            ),
            array(
                'label' => 'Parcours UTT',
                'student' => 'Allan Elleuch - 39678',
                'nbElements' => '5',
            ),
        );

        // replace this example code with whatever you need
        return $this->render('cursus/mes-cursus.html.twig', array(
            'currentPage' => 'mes-cursus',
            'cursus' => $cursus,
        ));
    }


    /**
     * @Route("/cursus/new/")
     */
    public function newCursusAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('cursus/new.html.twig', array(
            'currentPage' => "new",
        ));
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

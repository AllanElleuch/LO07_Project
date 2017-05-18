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
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;


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
     * @Route("/reglements/export/{id}")
     */
    public function exportOneReglementAction(Request $request, $id) {

        /* Récupération de l'objet cursus */
        $reglement = $this->getDoctrine()
            ->getRepository('AppBundle:Reglement')
            ->find($id);

        /* Si le chemin d'exportation n'existe pas, on le crée.
         * S'il existe déjà, on le vide pour ne pas stocker sur le serveur tous les fichiers exportés.
         * Seul le dernier fichier exporté est gardé sur le serveur.
         */
        if (!is_dir('./export_reglements')){
            mkdir('./export_reglements');
        } else {
            $files = glob('./export_reglements/*');
            foreach ($files as $file) {
                if (is_file($file)){
                    unlink($file);
                }
            }
        }

        /* Variables d'accès et d'ouverture du fichier csv */
        $filename = $reglement->getId() . "_" . $reglement->getLabel() . ".csv";
        $csvFilePath = "./export_reglements/" . $filename;
        $csvFile = fopen($csvFilePath, 'w');


        /* Écriture de la ligne intermédiaire */
        $headLine = array(
            "LABEL",
            $reglement->getLabel() ,
            "",
            "",
            ""
        );
        fputcsv($csvFile, $headLine, ";");

        $rules = $reglement->getRegles();

        foreach ($rules as $rule){
            $line = array(
                "R" . $rule->getId(),
                $this->getDoctrine()
                    ->getRepository('AppBundle:Agregat')
                    ->find($rule->getAgregat())->getLabel(),
                $rule->getCibleAgregat(),
                /* BEGIN : ISSUE HERE */
                $this->getDoctrine()
                    ->getRepository('AppBundle:Affectations')
                    ->find($rule->getAffectations())->getLabel(),
                /* END : ISSUE HERE */
                $rule->getSeuil(),
            );
            fputcsv($csvFile, $line, ";");
        }
        fclose($csvFile);


        // Téléchargement automatique du fichier
        $response = new BinaryFileResponse($csvFilePath);
        $response->headers->set('Content-Type', 'text/plain');
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT
        );

        return $response;

    }



    /**
     * @Route("/reglements/export/")
     */
    public function exportReglementsAction(Request $request)
    {

        $reglements = $this->getDoctrine()
            ->getRepository('AppBundle:Reglement')
            ->findAll();

        return $this->render('reglements/export.html.twig', array(
            'nav' => "reglements",
            'subnav' => "export",
            'reglements' => $reglements
        ));
    }

}

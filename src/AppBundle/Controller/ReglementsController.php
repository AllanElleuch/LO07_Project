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
            /* Respect de la numérotation R01 => R10 */
            $id = $rule->getId();
            if ($id < 10) {
                $firstElt = "R0" . $id;
            } else {
                $firstElt = "R" . $id;
            }

            $line = array(
                $firstElt,
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
     * @Route("/reglements/{cursusId}/{reglementId}")
     */
    public function ApplyReglementAction(Request $request, $cursusId, $reglementId)
    {
        /* Accès au règlement et au cursus concernés
         * ========================================= */
        $reglement = $this->getDoctrine()
            ->getRepository('AppBundle:Reglement')
            ->find($reglementId);

        $cursus = $this->getDoctrine()
            ->getRepository('AppBundle:Cursus')
            ->find($cursusId);

        /* Initialisation d'un tableau contenant les types de crédits
         * ========================================================== */
        $results = array(
            'CS_TCBR' => 0,
            'CS_FLBR' => 0,
            'CS_UTT'  => 0,
            'TM_TCBR' => 0,
            'TM_FLBR' => 0,
            'TM_UTT'  => 0,
            'ST_TCBR' => 0,
            'ST_FLBR' => 0,
            'EC_BR'   => 0,
            'ME_BR'   => 0,
            'HT_BR'   => 0,
            'HP_BR'   => 0,
            'SE'      => False,
            'NPML'    => False,
        );

        /* Lecture des crédits et booléens du cursus
         * ========================================= */
        foreach ($cursus->getElementsFormations() as $elt) {
            $utt          = $elt->getUtt();            // bool
            $credits      = $elt->getCredits();        // int
            $categories   = $elt->getCategories()->getLabel();     // CS, TM, ...
            $affectations = $elt->getAffectations()->getLabel();   // BR, TCBR, FLBR

            /* Lecture de la catégorie (CS, TM, ...) */
            switch ($categories) {
                case 'CS':
                    /* Si le cours est suivi à l'utt, on ajoute les crédits au compteur de la catégorie UTT*/
                    if ($utt) {
                        $results['CS_UTT'] += $credits;
                    }
                    /* Lecture de l'affectation (BR, TCBR, FLBR) */
                    switch ($affectations) {
                        case 'TCBR':
                            /* Ajout des crédits à catégorie_affectation */
                            $results['CS_TCBR'] += $credits;
                            break;
                        case 'FLBR':
                            $results['CS_FLBR'] += $credits;
                            break;
                        default:
                            break;
                    }
                    break;

                case 'TM':
                    /* Si le cours est suivi à l'utt, on ajoute les crédits au compteur de la catégorie UTT*/
                    if ($utt) {
                        $results['TM_UTT'] += $credits;
                    }
                    /* Lecture de l'affectation (BR, TCBR, FLBR) */
                    switch ($affectations) {
                        case 'TCBR':
                            /* Ajout des crédits à catégorie_affectation */
                            $results['TM_TCBR'] += $credits;
                            break;
                        case 'FLBR':
                            $results['TM_FLBR'] += $credits;
                            break;
                        default:
                            break;
                    }
                    break;

                case 'EC':
                    $results['EC_BR'] += $credits;
                    break;

                case 'ME':
                    $results['ME_BR'] += $credits;
                    break;

                case 'HT':
                    $results['HT_BR'] += $credits;
                    break;
                case 'CT':
                    $results['HT_BR'] += $credits;
                    break;

                case 'ST':
                    /* Lecture de l'affectation (BR, TCBR, FLBR) */
                    switch ($affectations) {
                        case 'TCBR':
                            /* Ajout des crédits à catégorie_affectation */
                            $results['ST_TCBR'] += $credits;
                            break;
                        case 'FLBR':
                            $results['ST_FLBR'] += $credits;
                            break;
                        default:
                            break;
                    }
                    break;

                case 'HP':
                    $results['HP_BR'] += $credits;
                    break;

                case 'SE':
                    $results['SE'] = True;
                    break;
                case 'NPML':
                    $results['NPML'] = True;
                    break;

                default:
                    break;
            }
        }

        /* Calcul des totaux
         * ================= */
        $results['CS+TM_UTT'] = $results['CS_UTT'] + $results['TM_UTT'];

        $results['CS_BR']     = $results['CS_TCBR'] + $results['CS_FLBR'];
        $results['TM_BR']     = $results['TM_TCBR'] + $results['TM_FLBR'];

        $results['ST_BR']     = $results['ST_TCBR'] + $results['ST_FLBR'];

        $results['ME+HT_BR']  = $results['ME_BR'] + $results['HT_BR'];

        $results['ALL']  =
            $results['CS_BR'] +
            $results['TM_BR'] +
            $results['EC_BR'] +
            $results['ME_BR'] +
            $results['HT_BR'] +
            $results['ST_BR'] +
            $results['HP_BR'];





        $mainArray = array();

        foreach ($reglement->getRegles() as $rule) {
            $mainArray[] = array(
                'ruleCibleAgregat' => $rule->getCibleAgregat(),
                'ruleAffectation'  => $rule->getAffectations()->getLabel(),
                'ruleRequired'     => $rule->getSeuil(),
                'ruleObtained'     => 6,
            );
        }

        return $this->render('reglements/applied-cursus.html.twig', array(
            'nav' => "reglements",
            'subnav' => "mes-reglements",
            'cursus' => $cursus,
            'reglementLabel' => $reglement->getLabel(),
            'results' => $mainArray,
            'credits' => $results
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


}

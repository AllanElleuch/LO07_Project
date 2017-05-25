<?php
/**
 * Created by PhpStorm.
 * User: corentinlaithier
 * Date: 17/04/2017
 * Time: 21:56
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Admissions;
use AppBundle\Entity\Affectations;
use AppBundle\Entity\Agregat;
use AppBundle\Entity\Filieres;
use AppBundle\Entity\Regle;
use AppBundle\Entity\Resultats;
use AppBundle\Entity\Reglement;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Categories;


class InitialisationBddController extends Controller {


    /**
     * @Route("/init_base/")
     */
    public function initBaseAction() {

        /**
         * PART 1
         * ======
         * Insertion des données de base dans la BDD
         *     - Catégories d'UE
         *     - Résultats possibles au UE
         *     - Affectations possibles d'une UE
         *     - Admissions possibles d'un étudiant
         *     - Filières possibles
         *     - Agrégats possibles pour les règlements
         */

        $res = $this->getDoctrine()->getRepository('AppBundle:Categories')->findAll();

        if (empty($res)){
            $categories = array('CS', 'TM', 'EC', 'CT', 'HT', 'ME', 'ST', 'SE', 'HP', 'NPML');

            foreach ($categories as $cat){
                $catObj = new Categories();
                $catObj->setLabel($cat);
                $em = $this->getDoctrine()->getManager();
                $em->persist($catObj);
                $em->flush();
            }
        }

        $res = $this->getDoctrine()->getRepository('AppBundle:Resultats')->findAll();

        if (empty($res)){
            $resultats = array('A', 'B', 'C', 'D', 'E', 'F', 'Fx', 'ABS', 'RES', 'ADM', 'EQU');

            foreach ($resultats as $resultat){
                $resObj = new Resultats();
                $resObj->setLabel($resultat);
                $em = $this->getDoctrine()->getManager();
                $em->persist($resObj);
                $em->flush();
            }
        }

        $res = $this->getDoctrine()->getRepository('AppBundle:Affectations')->findAll();

        if (empty($res)){
            $affectations = array('TC', 'BR', 'TCBR', 'FLBR', 'ALL');

            foreach ($affectations as $aff){
                $affObj = new Affectations();
                $affObj->setLabel($aff);
                $em = $this->getDoctrine()->getManager();
                $em->persist($affObj);
                $em->flush();
            }
        }

        $res = $this->getDoctrine()->getRepository('AppBundle:Admissions')->findAll();

        if (empty($res)){
            $adms = array('TC', 'BR');

            foreach ($adms as $adm){
                $admObj = new Admissions();
                $admObj->setLabel($adm);
                $em = $this->getDoctrine()->getManager();
                $em->persist($admObj);
                $em->flush();
            }
        }

        $res = $this->getDoctrine()->getRepository('AppBundle:Filieres')->findAll();

        if (empty($res)){
            $filieres = array('MPL', 'MSI', 'MRI', 'LIB', '?');

            foreach ($filieres as $fil){
                $filObj = new Filieres();
                $filObj->setLabel($fil);
                $em = $this->getDoctrine()->getManager();
                $em->persist($filObj);
                $em->flush();
            }
        }

        $res = $this->getDoctrine()->getRepository('AppBundle:Agregat')->findAll();

        if (empty($res)){
            $agregats = array('SUM', 'EXIST');

            foreach ($agregats as $agreg){
                $agregObj = new Agregat();
                $agregObj->setLabel($agreg);
                $em = $this->getDoctrine()->getManager();
                $em->persist($agregObj);
                $em->flush();
            }
        }





        /**
         * PART 2
         * ======
         * Création des règlements des études proposés par défaut.
         *     - R_ACTUEL_BR : règlement actuel (P17 et antérieur)
         *     - R_FUTUR_BR  : règlement futur  (A17?)
         */

        $res = $this->getDoctrine()->getRepository('AppBundle:Reglement')->findAll();

        if (empty($res)){
            /*
             * Création du règlement R_ACTUEL_BR
             * ---------------------------------
             */
            $regActuel = new Reglement();
            $regActuel->setLabel("R_ACTUEL_BR");
            $em = $this->getDoctrine()->getManager();
            $em->persist($regActuel);
            $em->flush();

            /*
             * Récupération d'instances d'éléments de la base de données
             * Permet de centraliser et de limiter le nombre de requêtes.
             */
            $agregatSum = $this->getDoctrine()
                ->getRepository('AppBundle:Agregat')
                ->findOneBy(array('label' => 'SUM'));

            $agregatExist = $this->getDoctrine()
                ->getRepository('AppBundle:Agregat')
                ->findOneBy(array('label' => 'EXIST'));

            $affBR = $this->getDoctrine()
                ->getRepository('AppBundle:Affectations')
                ->findOneBy(array('label' => 'BR'));

            $affTCBR = $this->getDoctrine()
                ->getRepository('AppBundle:Affectations')
                ->findOneBy(array('label' => 'TCBR'));

            $affFLBR = $this->getDoctrine()
                ->getRepository('AppBundle:Affectations')
                ->findOneBy(array('label' => 'FLBR'));

            $affALL = $this->getDoctrine()
                ->getRepository('AppBundle:Affectations')
                ->findOneBy(array('label' => 'ALL'));

            $reglementActuel = $this->getDoctrine()
                ->getRepository('AppBundle:Reglement')
                ->findOneBy(array('label' => 'R_ACTUEL_BR'));

            $rules = array();

            /*
             * Définition des règles
             */
            // R01;SUM;CS+TM;TCBR;54
            $R01 = new Regle();
            $R01->setAgregat($agregatSum);
            $R01->setCibleAgregat("CS+TM");
            $R01->setAffectations($affTCBR);
            $R01->setSeuil(54);
            $R01->setReglement($reglementActuel);
            $rules[] = $R01;


            // R02;SUM;CS+TM;FLBR;30
            $R02 = new Regle();
            $R02->setAgregat($agregatSum);
            $R02->setCibleAgregat("CS+TM");
            $R02->setAffectations($affFLBR);
            $R02->setSeuil(30);
            $R02->setReglement($reglementActuel);
            $rules[] = $R02;

            // R03;SUM;CS;BR;30
            $R03 = new Regle();
            $R03->setAgregat($agregatSum);
            $R03->setCibleAgregat("CS");
            $R03->setAffectations($affBR);
            $R03->setSeuil(30);
            $R03->setReglement($reglementActuel);
            $rules[] = $R03;

            // R04;SUM;TM;BR;30
            $R04 = new Regle();
            $R04->setAgregat($agregatSum);
            $R04->setCibleAgregat("TM");
            $R04->setAffectations($affBR);
            $R04->setSeuil(30);
            $R04->setReglement($reglementActuel);
            $rules[] = $R04;

            // R05;SUM;ST;TCBR;30
            $R05 = new Regle();
            $R05->setAgregat($agregatSum);
            $R05->setCibleAgregat("ST");
            $R05->setAffectations($affTCBR);
            $R05->setSeuil(30);
            $R05->setReglement($reglementActuel);
            $rules[] = $R05;

            // R06;SUM;ST;FLBR;30
            $R06 = new Regle();
            $R06->setAgregat($agregatSum);
            $R06->setCibleAgregat("ST");
            $R06->setAffectations($affFLBR);
            $R06->setSeuil(30);
            $R06->setReglement($reglementActuel);
            $rules[] = $R06;

            // R07;SUM;EC;BR;12
            $R07 = new Regle();
            $R07->setAgregat($agregatSum);
            $R07->setCibleAgregat("EC");
            $R07->setAffectations($affBR);
            $R07->setSeuil(12);
            $R07->setReglement($reglementActuel);
            $rules[] = $R07;

            // R08;SUM;ME;BR;4
            $R08 = new Regle();
            $R08->setAgregat($agregatSum);
            $R08->setCibleAgregat("ME");
            $R08->setAffectations($affBR);
            $R08->setSeuil(4);
            $R08->setReglement($reglementActuel);
            $rules[] = $R08;

            // R09;SUM;CT;BR;4
            $R09 = new Regle();
            $R09->setAgregat($agregatSum);
            $R09->setCibleAgregat("HT");
            $R09->setAffectations($affBR);
            $R09->setSeuil(4);
            $R09->setReglement($reglementActuel);
            $rules[] = $R09;

            // R10;SUM;ME+CT;BR;16
            $R10 = new Regle();
            $R10->setAgregat($agregatSum);
            $R10->setCibleAgregat("ME+HT");
            $R10->setAffectations($affBR);
            $R10->setSeuil(16);
            $R10->setReglement($reglementActuel);
            $rules[] = $R10;

            // R11;SUM;UTT(CS+TM);BR;60
            $R11 = new Regle();
            $R11->setAgregat($agregatSum);
            $R11->setCibleAgregat("UTT(CS+TM)");
            $R11->setAffectations($affBR);
            $R11->setSeuil(60);
            $R11->setReglement($reglementActuel);
            $rules[] = $R11;

            // R12;EXIST;SE;UTT;0
            $R12 = new Regle();
            $R12->setAgregat($agregatExist);
            $R12->setCibleAgregat("SE");
            $R12->setAffectations($affALL);
            $R12->setSeuil(0);
            $R12->setReglement($reglementActuel);
            $rules[] = $R12;

            // R13;EXIST;NPML;UTT;0
            $R13 = new Regle();
            $R13->setAgregat($agregatExist);
            $R13->setCibleAgregat("NPML");
            $R13->setAffectations($affALL);
            $R13->setSeuil(0);
            $R13->setReglement($reglementActuel);
            $rules[] = $R13;

            // R14;SUM;ALL;180
            $R14 = new Regle();
            $R14->setAgregat($agregatSum);
            $R14->setCibleAgregat("ALL");
            $R14->setAffectations($affALL);
            $R14->setSeuil(180);
            $R14->setReglement($reglementActuel);
            $rules[] = $R14;


            /*
             * Enregistrement dans la BDD
             */
            $em = $this->getDoctrine()->getManager();
            foreach ($rules as $rule) {
                $em->persist($rule);
            }
            $em->flush();






            /*
             * Création du règlement R_FUTUR_BR
             * --------------------------------
             */
            $regFutur = new Reglement();
            $regFutur->setLabel("R_FUTUR_BR");
            $em = $this->getDoctrine()->getManager();
            $em->persist($regFutur);
            $em->flush();

            $reglementFutur = $this->getDoctrine()
                ->getRepository('AppBundle:Reglement')
                ->findOneBy(array('label' => 'R_FUTUR_BR'));

            /*
             * Définition des règles
             */
            $rules = [];

            // R01;SUM;CS+TM;TCBR;42
            $R01 = new Regle();
            $R01->setAgregat($agregatSum);
            $R01->setCibleAgregat("CS+TM");
            $R01->setAffectations($affTCBR);
            $R01->setSeuil(42);
            $R01->setReglement($reglementFutur);
            $rules[] = $R01;

            // R02;SUM;CS+TM;FCBR;18
            $R02 = new Regle();
            $R02->setAgregat($agregatSum);
            $R02->setCibleAgregat("CS+TM");
            $R02->setAffectations($affFLBR);
            $R02->setSeuil(18);
            $R02->setReglement($reglementFutur);
            $rules[] = $R02;

            // R03;SUM;CS;BR;24
            $R03 = new Regle();
            $R03->setAgregat($agregatSum);
            $R03->setCibleAgregat("CS");
            $R03->setAffectations($affBR);
            $R03->setSeuil(24);
            $R03->setReglement($reglementFutur);
            $rules[] = $R03;

            // R04;SUM;TM;BR;24
            $R04 = new Regle();
            $R04->setAgregat($agregatSum);
            $R04->setCibleAgregat("TM");
            $R04->setAffectations($affBR);
            $R04->setSeuil(24);
            $R04->setReglement($reglementFutur);
            $rules[] = $R04;

            // R05;SUM;CS+TM;BR;84
            $R05 = new Regle();
            $R05->setAgregat($agregatSum);
            $R05->setCibleAgregat("CS+TM");
            $R05->setAffectations($affBR);
            $R05->setSeuil(84);
            $R05->setReglement($reglementFutur);
            $rules[] = $R05;

            // R06;SUM;ST;TCBR;30
            $R06 = new Regle();
            $R06->setAgregat($agregatSum);
            $R06->setCibleAgregat("ST");
            $R06->setAffectations($affTCBR);
            $R06->setSeuil(30);
            $R06->setReglement($reglementFutur);
            $rules[] = $R06;

            // R07;SUM;ST;FCBR;30
            $R07 = new Regle();
            $R07->setAgregat($agregatSum);
            $R07->setCibleAgregat("ST");
            $R07->setAffectations($affFLBR);
            $R07->setSeuil(30);
            $R07->setReglement($reglementFutur);
            $rules[] = $R07;

            // R08;SUM;EC;BR;12
            $R08 = new Regle();
            $R08->setAgregat($agregatSum);
            $R08->setCibleAgregat("EC");
            $R08->setAffectations($affBR);
            $R08->setSeuil(12);
            $R08->setReglement($reglementFutur);
            $rules[] = $R08;

            // R09;SUM;ME;BR;4
            $R09 = new Regle();
            $R09->setAgregat($agregatSum);
            $R09->setCibleAgregat("ME");
            $R09->setAffectations($affBR);
            $R09->setSeuil(4);
            $R09->setReglement($reglementFutur);
            $rules[] = $R09;

            // R10;SUM;CT;BR;4
            $R10 = new Regle();
            $R10->setAgregat($agregatSum);
            $R10->setCibleAgregat("CT");
            $R10->setAffectations($affBR);
            $R10->setSeuil(4);
            $R10->setReglement($reglementFutur);
            $rules[] = $R10;

            // R11;SUM;ME+CT;BR;16
            $R11 = new Regle();
            $R11->setAgregat($agregatSum);
            $R11->setCibleAgregat("ME+CT");
            $R11->setAffectations($affBR);
            $R11->setSeuil(16);
            $R11->setReglement($reglementFutur);
            $rules[] = $R11;

            // R12;SUM;UTT(CS+TM);BR;60
            $R12 = new Regle();
            $R12->setAgregat($agregatSum);
            $R12->setCibleAgregat("UTT(CS+TM)");
            $R12->setAffectations($affBR);
            $R12->setSeuil(60);
            $R12->setReglement($reglementFutur);
            $rules[] = $R12;

            // R13;EXIST;SE;BR;0
            $R13 = new Regle();
            $R13->setAgregat($agregatExist);
            $R13->setCibleAgregat("SE");
            $R13->setAffectations($affALL);
            $R13->setSeuil(0);
            $R13->setReglement($reglementFutur);
            $rules[] = $R13;

            // R14;EXIST;NPML;BR;0
            $R14 = new Regle();
            $R14->setAgregat($agregatExist);
            $R14->setCibleAgregat("NPML");
            $R14->setAffectations($affBR);
            $R14->setSeuil(0);
            $R14->setReglement($reglementFutur);
            $rules[] = $R14;

            // R15;SUM;ALL;180;
            $R15 = new Regle();
            $R15->setAgregat($agregatExist);
            $R15->setCibleAgregat("ALL");
            $R15->setAffectations($affALL);
            $R15->setSeuil(180);
            $R15->setReglement($reglementFutur);
            $rules[] = $R15;

            /*
             * Enregistrement dans la BDD
             */
            $em = $this->getDoctrine()->getManager();
            foreach ($rules as $rule) {
                $em->persist($rule);
            }
            $em->flush();
        }



        return $this->redirectToRoute('homepage');

    }

}

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
            $affectations = array('TC', 'BR', 'TCBR', 'FLBR');

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

            $R01 = new Regle();
            $R01->setAgregat(
                $this->getDoctrine()
                    ->getRepository('AppBundle:Agregat')
                    ->findOneBy(array('label' => 'SUM'))
            );
            $R01->setCibleAgregat("CS+TM");
            $R01->setAffectations(
                $this->getDoctrine()
                    ->getRepository('AppBundle:Affectations')
                    ->findOneBy(array('label' => 'TCBR'))
            );
            $R01->setSeuil(54);
            $R01->setReglement(
                $this->getDoctrine()
                    ->getRepository('AppBundle:Reglement')
                    ->findOneBy(array('label' => 'R_ACTUEL_BR'))
            );
            $em = $this->getDoctrine()->getManager();
            $em->persist($R01);
            $em->flush();


            /*
             * Création du règlement R_FUTUR_BR
             * --------------------------------
             */
            $regActuel = new Reglement();
            $regActuel->setLabel("R_FUTUR_BR");
            $em = $this->getDoctrine()->getManager();
            $em->persist($regActuel);
            $em->flush();
        }



        return $this->redirectToRoute('homepage');

    }
}

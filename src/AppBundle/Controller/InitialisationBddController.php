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
use AppBundle\Entity\Filieres;
use AppBundle\Entity\Resultats;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Categories;


class InitialisationBddController extends Controller {


    /**
     * @Route("/init_base/")
     */
    public function initBaseAction() {


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
            $affectations = array('TC', 'TCBR', 'FLBR');

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

        return $this->redirectToRoute('homepage');

    }
}
<?php
/**
 * Created by PhpStorm.
 * User: corentinlaithier
 * Date: 17/04/2017
 * Time: 21:56
 */

namespace AppBundle\Controller;

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

        return $this->redirectToRoute('homepage');

    }
}
<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Etudiants;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Cursus;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use AppBundle\Entity\ElementFormation;
use AppBundle\Form\Type\ElementFormationType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

// use Symfony\Component\Serializer\Serializer;
// use Symfony\Component\Serializer\Encoder\XmlEncoder;
// use Symfony\Component\Serializer\Encoder\JsonEncoder;
// use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;

class CursusController extends Controller {
    /**
     * affiche les cursus d'un étudiant
     * @Route("/cursus/mes-cursus/", name="homeCursus")
     */
    public function mesCursusAction(Request $request) {

        $cursus = $this->getDoctrine()
            ->getRepository('AppBundle:Cursus')
            ->findAll();

        $reglements = $this->getDoctrine()
            ->getRepository('AppBundle:Reglement')
            ->findAll();

        return $this->render('cursus/mes-cursus.html.twig', array(
            'nav' => "cursus",
            'subnav' => 'mes-cursus',
            'cursus' => $cursus,
            'reglements' => $reglements
        ));

    }


    /**
     * @Route("/cursus/delete/{id}")
     */
    public function deleteCursus(Cursus $cursus) {

        if (!$cursus) {
            throw $this->createNotFoundException('Cursus introuvable');
        }
        $em = $this->getDoctrine()->getEntityManager();

        $em->remove($cursus);
        $em->flush();
        return $this->redirectToRoute('homeCursus');

    }

    /**
     * @Route("/cursus/view/{id}")
     */
    public function viewOneCursus(Cursus $cursus, $id) {

        $cursus = $this->getDoctrine()
            ->getRepository('AppBundle:Cursus')
            ->find($id);

        $reglements = $this->getDoctrine()
            ->getRepository('AppBundle:Reglement')
            ->findAll();

        $cursusElements =  $this->getDoctrine()
            ->getRepository("AppBundle:ElementFormation")
            ->findBy(array("cursus" => $id));


        return $this->render('cursus/view.html.twig', array(
            'nav' => "cursus",
            'subnav' => 'mes-cursus',
            'cursus' => $cursus,
            'cursusElements' => $cursusElements,
            'reglements' => $reglements,
        ));

    }

    /**
     * @Route("/cursus/update/{id}")
     */
    public function updateCursus(Request $request, $id) {


        // create a cursus and give it some dummy data for this example
        $cursus = $this->getDoctrine()
            ->getRepository('AppBundle:Cursus')
            ->find($id);

        if (!$cursus) {
            throw $this->createNotFoundException('Aucun cursus à édité.');
        }


        $form = $this->createFormBuilder($cursus)
        ->add('label', TextType::class, array('label' => 'Nom du cursus', 'attr' => array('placeholder' => 'Mon cursus UTT', 'class' => 'form-control')))

          ->add('etudiant', EntityType::class, array(
              'class' => 'AppBundle:Etudiants',
              'choice_label' => 'uniqueName',
              'label' => "Étudiant"))
        ->add('elementsFormations', CollectionType::class, array(
        'entry_type'   => ElementFormationType::class,
        'allow_add'    => true,
        ))
        ->add('envoyer', SubmitType::class, array('label' => 'Modifier le cursus'))

        ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $cursus = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $em = $this->getDoctrine()->getManager();
            $em->persist($cursus);
            $em->flush();

            return $this->redirectToRoute('homepage');


        }

        // $encoders = array(new XmlEncoder(), new JsonEncoder());
        // $normalizers = array(new ObjectNormalizer());
        // $serializer = new Serializer($normalizers, $encoders);
        // $json =  $serializer->serialize($cursus->getelementsFormations(), 'json');
        // $normalizer = new PropertyNormalizer();
        // $json      = $normalizer->normalize( $cursus );
        // $arrayCursus = array( );
        $arr = array();
        foreach ($cursus->getelementsFormations() as $elem  ) {
            // dump($elem);
            $semseq=$elem->getSemSeq();
            $semlabel=$elem->getSemLabel();

             if(!array_key_exists($semseq,$arr)){
                 $add = array($semseq=>array() );
                 array_push($arr,$add);

                 array_push($arr[$semseq-1],array("semlabel"=>$semlabel ));
                 array_push($arr[$semseq-1],array("semseq"=>$semseq ));

                // array_push($arr[$semseq-1], $semlabel);


             }
             array_push($arr[$semseq-1], $elem->toArray());
            //  print($elem->getCredits());
            //  print($elem->getAffectations());
            //  print($elem->getCategories());
            //  print($elem->getResultats());
            //dump($elem->toArray());
        }
        // dump($arr);
        // $json = json_encode($arr);
        // print($json);
        // echo json_encode($arr);

        // $elemFormation = $cursus->getelementsFormations();

        // TODO : faire une liste avec les semestre => semseq
        // TODO : faire une liste semseq ==> [ elemformation1, elemFormation2]

        $formview=$form->createView();
        // dump($formview);


        $it = $formview->getIterator();
        $listElemFormView = array();
        while( $it->valid() ){
            $item =$it->current() ;
            $vars = $item->vars;
            if(array_key_exists("name",$vars)){

                if($vars["name"] == "elementsFormations"){

                    foreach ($item as $key => $value) {
                        dump($value);
                        $lab = $value["sem_label"];
                        $currentlabel = $value["sem_label"]->vars["value"];
                        if(!array_key_exists($currentlabel,$listElemFormView)){

                            $listElemFormView[$currentlabel]=array();
                        }
                        array_push($listElemFormView[$currentlabel], $value);
                    }
                }
            }
        $it->next();
        }
// dump($listElemFormView);

        $listSem=array( );
        foreach ($cursus->getelementsFormations() as $elemForm ) {

                $label = $elemForm->getSemLabel();
                $semseq = $elemForm->getSemSeq();
                $listSem[$label]=$semseq;

        }





        // dump($listSem);
        return $this->render('cursus/new.html.twig', array(
            'form'   => $form->createView(),
            'nav'    => "cursus",
            'subnav' => "new",
            'cursus' => $cursus,
            'coursParSemestre' => $listElemFormView,
            'listSem'=>$listSem,
        ));


    }


    /**
     * @Route("/cursus/duplicate/{id}")
     */
    public function duplicateCursus(Request $request, $id) {


        // create a cursus and give it some dummy data for this example
        $cursus = $this->getDoctrine()
            ->getRepository('AppBundle:Cursus')
            ->find($id);

        $elemsFormation = $this->getDoctrine()
            ->getRepository('AppBundle:ElementFormation')
            ->findBy(array(
                'cursus' => $cursus,
            ));

        $em = $this->getDoctrine()->getManager();
        $newCursus = clone $cursus;
        $em->persist($newCursus);

        foreach ($elemsFormation as $elt) {
            $newElt = clone $elt;
            $newElt->setCursus($newCursus);
            $em->persist($newElt);
        }
        $em->flush();


        return $this->redirectToRoute('homeCursus');


    }


    /**
     * @Route("/cursus/new/")
     */
    public function newCursusAction(Request $request) {

        // create a cursus and give it some dummy data for this example
        $cursus = new Cursus();
        //$cursus->setLabel('Mon cursus de ...');

        // $e = new ElementFormation();
        // $e->setCursus($cursus);
        // $e->setSemLabel('a');
        // $e->setSigle('a');
        // $e->setUtt(true);
        // $e->setProfil(true);
        // $e->setSemSeq(2);
        // $e->setCredit(1);
        //
        // $cursus->addElementsFormation($e);
        //

        $form = $this->createFormBuilder($cursus)
            ->add('label', TextType::class, array('label' => 'Nom du cursus', 'attr' => array('placeholder' => 'Mon cursus UTT', 'class' => 'form-control')))

            ->add('etudiant', EntityType::class, array(
                'class' => 'AppBundle:Etudiants',
                'choice_label' => 'uniqueName',
                'label' => "Étudiant"))
            ->add('elementsFormations', CollectionType::class, array(
            'entry_type'   => ElementFormationType::class,
            'allow_add'    => true,
            ))
            ->add('envoyer', SubmitType::class, array('label' => 'Créer un cursus'))

            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $cursus = $form->getData();

            foreach($cursus->getelementsFormations() as $elemFormation){
              $elemFormation->setCursus($cursus);
            }

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $em = $this->getDoctrine()->getManager();

            $em->persist($cursus);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        $formview=$form->createView();
        $listElemFormView = array();
        $listSem=array( );



        //  $json = json_encode($cursus);
        return $this->render('cursus/new.html.twig', array(
            'form' => $formview,
            'nav' => "cursus",
            'subnav' => "new",
            'cursus' => $cursus,
            'coursParSemestre' => $listElemFormView,
            'listSem'=>$listSem,
            ));


    }


    /**
     * @Route("/cursus/import/")
     */
    public function importCursusAction(Request $request) {

        /*Initialisation de variables utilisées pour la vue */
        $notifClass = 'success';
        $notifBody  = 'Le fichier a été importé.';

        $form = $this->createFormBuilder()
            ->add('nomCursus', TextType::class, array(
                'label' => 'Nom du cursus',
                'attr' => array(
                    'placeholder' => 'Mon cursus UTT',
                    'class' => 'form-control'
                )
            ))
            ->add('submitFile', FileType::class, array('label' => 'Choix du fichier'))
            ->add('envoyer', SubmitType::class, array('label' => 'Envoyer le fichier'))
            ->getForm();

        if ($request->getMethod('post') == 'POST') {
            // Bind request to the form
            $form->handleRequest($request);

            // If form is valid
            if ($form->isValid()) {
                // Get file
                $file = $form->get('submitFile');
                $file = $file->getData();

                $label = $form->get('nomCursus');
                $label = $label->getData();

                if (($handle = fopen($file->getRealPath(), "r")) !== FALSE) {
                    /*
                     * Premier parcours du fichier pour récupérer les données concernant l'étudiant.
                     * On vérifiera ensuite s'il existe dans la BDD.
                     *
                     * !! Seul l'en-tête est lu, la boucle de lecture s'arrête avant la description du cursus.
                     */
                    while(($row = fgetcsv($handle)) !== FALSE) {
                        $data = explode(";", $row[0]);

                        switch ($data[0]){
                            case "ID":
                                 $stdNumber = $data[1];
                                break;
                            case "NO":
                                $stdName = ucfirst(strtolower($data[1]));
                                break;
                            case "PR":
                                $stdFirstName = ucfirst(strtolower($data[1]));
                                break;
                            case "AD":
                                $possibleValues = array("BR", 'TC');
                                if (in_array($data[1], $possibleValues)){
                                    $stdAdmin = $data[1];
                                } else {
                                    $notifClass = 'danger';
                                    $notifBody = "Le champ AD doit être BR ou TC.";
                                }
                                break;
                            case "FI":
                                $possibleValues = array("MPL", "MRI", "MSI", "LIB", "?");
                                if (in_array($data[1], $possibleValues)){
                                    $stdFiliere = $data[1];
                                } else {
                                    $notifClass = 'danger';
                                    $notifBody = "Le champ FI doit être MPL, MRI, MSI, LIB ou ?.";
                                }
                                break;
                            case "==":
                                break;

                            /* Lecture d'un nouvel élément de formation
                             * + Append à la collection d'elements de formation */
                            case "EL":
                                $eltFormation = new ElementFormation();
                                $eltFormation->setSemSeq($data[1]);
                                $eltFormation->setSemLabel(strtoupper($data[2]));
                                $eltFormation->setSigle(strtoupper($data[3]));

                                $categorie = $this->getDoctrine()
                                    ->getRepository('AppBundle:Categories')
                                    ->findOneBy(array('label' => $data[4]));
                                $eltFormation->setCategories($categorie);

                                $affectation = $this->getDoctrine()
                                    ->getRepository('AppBundle:Affectations')
                                    ->findOneBy(array('label' => $data[5]));
                                $eltFormation->setAffectations($affectation);

                                if ($data[6] == 'Y'){
                                    $utt = true;
                                } else {
                                    $utt = false;
                                }
                                $eltFormation->setUtt($utt);

                                if ($data[7] == 'Y'){
                                    $profil = true;
                                } else {
                                    $profil = false;
                                }
                                $eltFormation->setProfil($profil);

                                $eltFormation->setCredits($data[8]);

                                $resultat = $this->getDoctrine()
                                    ->getRepository('AppBundle:Resultats')
                                    ->findOneBy(array('label' => $data[9]));
                                $eltFormation->setResultats($resultat);

                                $eltsFormation[] = $eltFormation;
                                break;
                            default:
                                break;
                        }
                    }
                    /* Recherche de l'étudiant dans la base de données à partir des informations lues dans le fichier. */
                    $etudiant = $this->getDoctrine()
                        ->getRepository('AppBundle:Etudiants')
                        ->findOneBy(array('numEtu' => $stdNumber));
                    /* Si l'étudiant n'existe pas, il est créé. */
                    if (empty($etudiant)){
                        $newStudent = new Etudiants();
                        $newStudent->setNom($stdName);
                        $newStudent->setPrenom($stdFirstName);
                        $newStudent->setNumEtu($stdNumber);

                        $filiere = $this->getDoctrine()
                            ->getRepository('AppBundle:Filieres')
                            ->findOneBy(array('label' => $stdFiliere));
                        $newStudent->setFilieres($filiere);

                        $admission = $this->getDoctrine()
                            ->getRepository('AppBundle:Admissions')
                            ->findOneBy(array('label' => $stdAdmin));
                        $newStudent->setAdmissions($admission);

                        $em = $this->getDoctrine()->getManager();
                        $em->persist($newStudent);
                        $etudiant = $newStudent;
                    }


                    $cursus = new Cursus();


                    $cursus->setLabel($label);
                    $cursus->setEtudiant($etudiant);
                    $em = $this->getDoctrine()->getManager();
                    foreach ($eltsFormation as $element){
                        $element->setCursus($cursus);
                        $em->persist($element);
                    }
                    $em->persist($cursus);
                    $em->flush();

                    return $this->render('cursus/import.html.twig', array(
                        'nav' => "cursus",
                        'subnav' => "import",
                        'notifClass' => $notifClass,
                        'notif' => $notifBody,
                        'form' => $form->createView(),
                    ));
                }
            }

        }

        // replace this example code with whatever you need
        return $this->render('cursus/import.html.twig', array(
            'nav' => "cursus",
            'subnav' => "import",
            'form' => $form->createView(),
        ));
    }


    /**
     * @Route("/cursus/export/{id}")
     */
    public function exportOneCursusAction(Request $request, $id) {

        /* Récupération de l'objet cursus */
        $cursus = $this->getDoctrine()
            ->getRepository('AppBundle:Cursus')
            ->find($id);

        /* Récupération de l'étudiant associé au cursus pour créer l'en-tête */
        $etudiant = $cursus->getEtudiant();

        /* Si le chemin d'exportation n'existe pas, on le crée.
         * S'il existe déjà, on le vide pour ne pas stocker sur le serveur tous les fichiers exportés.
         * Seul le dernier fichier exporté est gardé sur le serveur.
         */
        if (!is_dir('./export_cursus')){
            mkdir('./export_cursus');
        } else {
            $files = glob('./export_cursus/*');
            foreach ($files as $file) {
                if (is_file($file)){
                    unlink($file);
                }
            }
        }

        /* Variables d'accès et d'ouverture du fichier csv */
        $filename = $etudiant->getId() . "_" . $etudiant->getNom() . "-" . $etudiant->getPrenom() . ".csv";
        $csvFilePath = "./export_cursus/" . $filename;
        $csvFile = fopen($csvFilePath, 'w');

        /* Construction de l'en-tête */
        $headerLines = array(
            "lineId" => array("ID", $etudiant->getId(), "", "", "", "", "", "", "", ""),
            "lineNom" => array("NO", $etudiant->getNom(), "", "", "", "", "", "", "", ""),
            "linePrenom" => array("PR", $etudiant->getPrenom(), "", "", "", "", "", "", "", ""),
            "lineAdm"    => array("AD",
                $this->getDoctrine()
                    ->getRepository('AppBundle:Admissions')
                    ->find($etudiant->getAdmissions())->getLabel(),
                "", "", "", "", "", "", "", ""),
            "lineFil"    => array("FI",
                $this->getDoctrine()
                    ->getRepository('AppBundle:Filieres')
                    ->find($etudiant->getFilieres())->getLabel(),
                "", "", "", "", "", "", "", ""),
        );

        /* Écriture de l'en-tête dans le fichier csv */
        foreach ($headerLines as $headLine){
            fputcsv($csvFile, $headLine, ";");
        }

        /* Écriture de la ligne intermédiaire */
        $transitionLine = array(
            "==", "s_seq", "s_label", "sigle", "categorie", "affectation", "utt", "profil", "credit", "resultat"
        );
        fputcsv($csvFile, $transitionLine, ";");

        $elements = $cursus->getElementsFormations();

        foreach ($elements as $elt){
            $utt = $elt->getUtt() == 1 ? 'Y' : 'N';
            $profil = $elt->getProfil() == 1 ? 'Y' : 'N';
            $line = array(
                "EL",
                $elt->getSemSeq(),
                $elt->getSemLabel(),
                $elt->getSigle(),
                $this->getDoctrine()
                    ->getRepository('AppBundle:Categories')
                    ->find($elt->getCategories())->getLabel(),
                $this->getDoctrine()
                    ->getRepository('AppBundle:Affectations')
                    ->find($elt->getAffectations())->getLabel(),
                $utt,
                $profil,
                $elt->getCredits(),
                $this->getDoctrine()
                    ->getRepository('AppBundle:Resultats')
                    ->find($elt->getResultats())->getLabel(),

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
     * affiche les cursus d'un étudiant
     * @Route("help/cursus/import/")
     */
    public function helpImportCursusAction(Request $request) {

        return $this->render('help/import-cursus.html.twig', array(
            'nav' => "", // nav est appellée dans base.html.twig et DOIT être définie
        ));

    }

    /**
     * @Route("/phpinfo")
     */
    public function phpInfoAction(Request $request) {

        phpinfo();

    }


}

<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Etudiants;
use AppBundle\Entity\Cursus;
use AppBundle\Entity\ElementFormation;
use AppBundle\Entity\CatalogueUE;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use AppBundle\Form\Type\ElementFormationType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
// For annotations
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Doctrine\Common\Collections\ArrayCollection;

class CursusController extends Controller {

    /**
     * mesCursusAction
     * ===============
     * Affiche la liste des cursus du système
     * @Route("/cursus/mes-cursus/", name="homeCursus")
     */
    public function mesCursusAction(Request $request) {

        /* Récupération de tous les cursus dans la base de données */
        $cursus = $this->getDoctrine()
            ->getRepository('AppBundle:Cursus')
            ->findAll();

        /* Récupération des règlements
         * Utilisé pour les boutons 'Appliquer règlement' sur les cursus
         */
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
     * deleteCursusAction
     * ==================
     * Suppression d'un cursus identifié par son id passé via l'URL
     * @Route("/cursus/delete/{id}")
     */
    public function deleteCursusAction(Cursus $cursus) {

        /* Vérification de l'existence du cursus à supprimer
         * S'il n'existe pas, une exception est levée.
         */
        if (!$cursus) {
            throw $this->createNotFoundException('Cursus introuvable');
        }

        /* Suppression du cursus */
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($cursus);
        $em->flush();

        return $this->redirectToRoute('homeCursus');

    }

    /**
     * viewOneCursusAction
     * ===================
     * Permet d'afficher en détail un cursus identifié par son id passé dans l'URL
     * @Route("/cursus/view/{id}")
     */
    public function viewOneCursusAction(Cursus $cursus, $id) {

        /* Récupération du cursus à partir de son id */
        $cursus = $this->getDoctrine()
            ->getRepository('AppBundle:Cursus')
            ->find($id);

        /* Récupération des règlements
         * Utilisé pour les boutons 'Appliquer règlement' sur les cursus
         */
        $reglements = $this->getDoctrine()
            ->getRepository('AppBundle:Reglement')
            ->findAll();

        /* Récupération de tous les éléments de formation associés au cursus */
        $cursusElements =  $this->getDoctrine()
            ->getRepository("AppBundle:ElementFormation")
            ->findBy(array("cursus" => $id), array('sem_seq' => 'ASC'));

        return $this->render('cursus/view.html.twig', array(
            'nav' => "cursus",
            'subnav' => 'mes-cursus',
            'cursus' => $cursus,
            'cursusElements' => $cursusElements,
            'reglements' => $reglements,
        ));

    }

    /**
     * updateCursusAction
     * ==================
     * Modification d'un cursus identifié par son id dans l'URL.
     * @Route("/cursus/update/{id}")
     */
    public function updateCursusAction(Request $request, $id) {


        /* Récupération du cursus à partir de son id */
        $cursus = $this->getDoctrine()
            ->getRepository('AppBundle:Cursus')
            ->find($id);

        /* Vérification de l'existence du cursus à modifier
         * S'il n'existe pas, une exception est levée.
         */
        if (!$cursus) {
            throw $this->createNotFoundException('Aucun cursus à édité.');
        }

        /* Création du formulaire */
        $form = $this->createFormBuilder($cursus)
            ->add('label', TextType::class, array('label' => 'Nom du cursus', 'attr' => array('placeholder' => 'Mon cursus UTT', 'class' => 'form-control')))
            ->add('etudiant', EntityType::class, array(
                'class' => 'AppBundle:Etudiants',
                'choice_label' => 'uniqueName',
                'label' => "Étudiant",
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('elementsFormations', CollectionType::class, array(
                'entry_type'   => ElementFormationType::class,
                'allow_add'    => true,
                'allow_delete' => true,
            ))
            ->add('envoyer', SubmitType::class, array(
                'label' => 'Enregister les modifications',
                'attr' => array(
                    'class' => 'btn btn-sm btn-outline-success'
                )
            ))
            ->getForm();

        $form->handleRequest($request);

        /* Lors de la soumission du formulaire ...*/
        if ($form->isSubmitted() && $form->isValid()) {

            /* Lecture des données du formulaire */
            $cursusForm = $form->getData();

            /* Lecture des données déjà présentes sur le cursus dans la BDD */
            $cursusBD = $this->getDoctrine()
                ->getRepository('AppBundle:Cursus')
                ->find($id);
            $originalElemFormation = $cursusBD->getElementsFormations();



            /* Mise à jour du cursus avec les différences entre le formulaire et la BDD */
            foreach ($originalElemFormation as $elem) {
                if (false === $cursusForm->getElementsFormations()->contains($elem)) {
                    $cursus->getElementsFormations()->removeElement($elem);
                    $em->remove($elem);
                } else{
                    foreach($cursus->getelementsFormations() as $elemFormation){
                        $elemFormation->setCursus($cursus);
                    }
                }
            }

            /* Écriture dans la base de données */
            $em = $this->getDoctrine()->getManager();
            $em->persist($cursus);
            $em->flush();
            return $this->redirectToRoute('homeCursus'); //redirection sur la view des cursus
        }

        /* Récupération de tous les éléments de formation du cursus */
        $cursusElements =  $this->getDoctrine()
            ->getRepository("AppBundle:ElementFormation")
            ->findBy(array("cursus" => $id), array('sem_seq' => 'ASC'));


        $arr = array();
        foreach ($cursusElements as $elem  ) {
            $semseq=$elem->getSemSeq();
            $semlabel=$elem->getSemLabel();

            if(!array_key_exists($semseq,$arr)){
                $add = array($semseq=>array() );
                array_push($arr,$add);
                array_push($arr[$semseq-1],array("semlabel"=>$semlabel ));
                array_push($arr[$semseq-1],array("semseq"=>$semseq ));
            }
            array_push($arr[$semseq-1], $elem->toArray());
        }

        // création d'un array efficacement afficher les semestre/cours
        $formview=$form->createView();
        $it = $formview->getIterator();
        $listElemFormView = array();
        while( $it->valid() ){
            $item =$it->current() ;
            $vars = $item->vars;
            if(array_key_exists("name",$vars)){

                if($vars["name"] == "elementsFormations"){

                    foreach ($item as $key => $value) {
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

        $listSem=array( );
        foreach ($cursusElements as $elemForm ) {
                $label = $elemForm->getSemLabel();
                $semseq = $elemForm->getSemSeq();
                $listSem[$label]=$semseq;
        }

        // création d'un array json pour le catalogue d'ue nécessaire à l'autocomplétion
        $listUV = $this->getDoctrine()
            ->getRepository('AppBundle:CatalogueUE')
            ->findAll();
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer(array($normalizer));

        $data = $serializer->normalize($listUV, null, array('groups' => array('labelUV')));
        $json_listUV = json_encode($data, JSON_UNESCAPED_UNICODE);

        return $this->render('cursus/new.html.twig', array(
            'form'   => $formview,
            'nav'    => "cursus",
            'subnav' => "new",
            'cursus' => $cursus,
            'coursParSemestre' => $listElemFormView,
            'listSem'=>$listSem,
            'listUV'=>$json_listUV,

        ));


    }


    /**
     * duplicateCursusAction
     * =====================
     * Duplique un cursus identifié par l'id passé dans l'URL
     * @Route("/cursus/duplicate/{id}")
     */
    public function duplicateCursusAction(Request $request, $id) {


        /* Récupération du cursus à dupliquer */
        $cursus = $this->getDoctrine()
            ->getRepository('AppBundle:Cursus')
            ->find($id);

        /* Récupération des éléments de formation liés au cursus à dupliquer */
        $elemsFormation = $this->getDoctrine()
            ->getRepository('AppBundle:ElementFormation')
            ->findBy(array(
                'cursus' => $cursus,
            ));

        /* Duplication du cursus */
        $em = $this->getDoctrine()->getManager();
        $newCursus = clone $cursus;
        $em->persist($newCursus);

        /* Duplication des éléments de formation
         * Affectation des nouveaux éléments de formation au nouveau cursus
         */
        foreach ($elemsFormation as $elt) {
            $newElt = clone $elt;
            $newElt->setCursus($newCursus);
            $em->persist($newElt);
        }

        $em->flush();

        return $this->redirectToRoute('homeCursus');
    }


    /**
     * newCursusAction
     * ===============
     * Crée un nouveau cursus
     * @Route("/cursus/new/")
     */
    public function newCursusAction(Request $request) {

        /* Création d'un nouveau cursus */
        $cursus = new Cursus();

        /* Création du formulaire */
        $form = $this->createFormBuilder($cursus)
            ->add('label', TextType::class, array(
                'label' => 'Nom du cursus',
                'attr' => array(
                    'placeholder' => 'Mon cursus UTT',
                    'class' => 'form-control'
                )
            ))
            ->add('etudiant', EntityType::class, array(
                'class' => 'AppBundle:Etudiants',
                'choice_label' => 'uniqueName',
                'label' => "Étudiant",
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('elementsFormations', CollectionType::class, array(
                'entry_type'   => ElementFormationType::class,
                'allow_add'    => true,
                'allow_delete' => true,
            ))
            ->add('envoyer', SubmitType::class, array(
                'label' => 'Créer le cursus',
                'attr' => array(
                    'class' => 'btn btn-sm btn-outline-success'
                )
            ))
            ->getForm();

        $form->handleRequest($request);

        /* Lorsque le formulaire est soumis ... */
        if ($form->isSubmitted() && $form->isValid()) {

            $cursus = $form->getData();
            $em = $this->getDoctrine()->getManager();

            foreach($cursus->getelementsFormations() as $elemFormation){
                $elemFormation->setCursus($cursus);

                /* Apprentissage des labels d'UE :
                 * On recherche si l'UE existe déjà dans la table CatalogueUE.
                 * Si non, on la crée
                 */
                $res = $this->getDoctrine()
                    ->getRepository('AppBundle:CatalogueUE')
                    ->findOneBy(array('label' => $elemFormation->getSigle()));

                if (empty($res)) {
                    $newUE = new CatalogueUE();
                    $newUE->setLabel($elemFormation->getSigle());
                    $em->persist($newUE);
                }
            }
            $em->persist($cursus);
            $em->flush();
            return $this->redirectToRoute('homeCursus');
        }


        $formview=$form->createView();
        $listElemFormView = array();
        $listSem=array( );

        $listUV = $this->getDoctrine()
            ->getRepository('AppBundle:CatalogueUE')
            ->findAll();

        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer(array($normalizer));

        $data = $serializer->normalize($listUV, null, array('groups' => array('labelUV')));
        $json_listUV = json_encode($data, JSON_UNESCAPED_UNICODE);

        return $this->render('cursus/new.html.twig', array(
            'form' => $formview,
            'nav' => "cursus",
            'subnav' => "new",
            'cursus' => $cursus,
            'coursParSemestre' => $listElemFormView,
            'listSem'=>$listSem,
            'listUV'=>$json_listUV,
            ));
    }


    /** importCursusAction
     *  ==================
     *  Importation d'un cursus au format csv
     * @Route("/cursus/import/")
     */
    public function importCursusAction(Request $request) {

        /* Initialisation de variables utilisées pour la value
         * On part du principe que tout se passera bien
         * Une erreur changera la classe et le message de la notification
         */
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
            $form->handleRequest($request);

            if ($form->isValid()) {
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
                        /* Affectation de chaque élément lu au nouveau cursus */
                        $element->setCursus($cursus);
                        $em->persist($element);

                        /* Apprentissage des labels d'UE :
                         * On recherche si l'UE existe déjà dans la table CatalogueUE.
                         * Si non, on la crée
                         */
                        $res = $this->getDoctrine()
                            ->getRepository('AppBundle:CatalogueUE')
                            ->findOneBy(array('label' => $element->getSigle()));
                        if (empty($res)) {
                            $newUE = new CatalogueUE();
                            $newUE->setLabel($element->getSigle());
                            $em->persist($newUE);
                        }
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

        return $this->render('cursus/import.html.twig', array(
            'nav' => "cursus",
            'subnav' => "import",
            'form' => $form->createView(),
        ));
    }


    /**
     * exportOneCursusAction
     * =====================
     * Exporte un cursus choisi au format csv
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
     * helpImportCursusAction
     * ======================
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

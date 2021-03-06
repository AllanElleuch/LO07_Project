<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="elementFormation")
 */
class ElementFormation
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\Column(type="smallint", length=100)
     */
    private $sem_seq;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $sem_label;


    /**
     * @ORM\Column(type="string", length=100)
     */
    private $sigle;

    /**
     * @ORM\Column(type="boolean", length=100)
     */
    private $utt;

    /**
     * @ORM\Column(type="boolean", length=100)
     */
    private $profil;

    /**
     * @ORM\Column(type="smallint", length=100)
     */
    private $credits;

    /**
     * catégories : CS, TM, EC, CT, HT, ME, ST, SE, HP, NPML
     * plusieurs éléments de fomation on une Categories
     * @ORM\ManyToOne(targetEntity="Categories", inversedBy="elementsFormation")
     * @ORM\JoinColumn(name="categories_id", referencedColumnName="id")
     */
       private $categories;

    /**
     * $affectations :  TC, TCBR, FLBR
     * plusieurs éléments de fomation on une affectations
     * @ORM\ManyToOne(targetEntity="Affectations")
     * @ORM\JoinColumn(name="affectations_id", referencedColumnName="id")
     */
    private $affectations;

    /**
     * résultats : A, B, C, D, E, F, FX, ABS, RES, ADM
     * plusieurs éléments de fomation on un Resultats
     * @ORM\ManyToOne(targetEntity="Resultats", inversedBy="elementsFormation")
     * @ORM\JoinColumn(name="resultats_id", referencedColumnName="id")
     */
      private $resultats;

    /**
     * Plusieurs élément de formations appartiennent un seul cursus.
     * @ORM\ManyToOne(targetEntity="Cursus", inversedBy="elementsFormations",cascade={"all"})
     * @ORM\JoinColumn(name="cursus_id", referencedColumnName="id")
     */
    private $cursus;

    public function toArray(){

        $arr = array();
        array_push($arr, $this->getSigle() );
        array_push($arr, $this->getCredits() );
        array_push($arr, $this->getAffectations()->getId() );
        array_push($arr, $this->getCategories()->getId() );
        array_push($arr, $this->getResultats()->getId() );
        array_push($arr, $this->getUtt() );
        array_push($arr, $this->getProfil() );
        // array_push($arr, $this->getCategories() );
        // array_push($arr, $this->getAffectations() );
        // array_push($arr, $this->getResultats() );
        // array_push( $this->getSemLabel() ,$arr);
        // array_push( $this->getSigle() ,$arr);
        // array_push( $this->getUtt() ,$arr);
        // array_push( $this->getProfil() ,$arr);
        // array_push( $this->getCredits() ,$arr);
        // array_push( $this->getCategories() ,$arr);
        // array_push( $this->getAffectations() ,$arr);
        // array_push( $this->getResultats() ,$arr);
        return $arr;
    }



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set semSeq
     *
     * @param integer $semSeq
     *
     * @return ElementFormation
     */
    public function setSemSeq($semSeq)
    {
        $this->sem_seq = $semSeq;

        return $this;
    }

    /**
     * Get semSeq
     *
     * @return integer
     */
    public function getSemSeq()
    {
        return $this->sem_seq;
    }

    /**
     * Set semLabel
     *
     * @param string $semLabel
     *
     * @return ElementFormation
     */
    public function setSemLabel($semLabel)
    {
        $this->sem_label = $semLabel;

        return $this;
    }

    /**
     * Get semLabel
     *
     * @return string
     */
    public function getSemLabel()
    {
        return $this->sem_label;
    }

    /**
     * Set sigle
     *
     * @param string $sigle
     *
     * @return ElementFormation
     */
    public function setSigle($sigle)
    {
        $this->sigle = $sigle;

        return $this;
    }

    /**
     * Get sigle
     *
     * @return string
     */
    public function getSigle()
    {
        return $this->sigle;
    }

    /**
     * Set utt
     *
     * @param boolean $utt
     *
     * @return ElementFormation
     */
    public function setUtt($utt)
    {
        $this->utt = $utt;

        return $this;
    }

    /**
     * Get utt
     *
     * @return boolean
     */
    public function getUtt()
    {
        return $this->utt;
    }

    /**
     * Set profil
     *
     * @param boolean $profil
     *
     * @return ElementFormation
     */
    public function setProfil($profil)
    {
        $this->profil = $profil;

        return $this;
    }

    /**
     * Get profil
     *
     * @return boolean
     */
    public function getProfil()
    {
        return $this->profil;
    }

    /**
     * Set credits
     *
     * @param integer $credits
     *
     * @return ElementFormation
     */
    public function setCredits($credits)
    {
        $this->credits = $credits;

        return $this;
    }

    /**
     * Get credits
     *
     * @return integer
     */
    public function getCredits()
    {
        return $this->credits;
    }

    /**
     * Set categories
     *
     * @param \AppBundle\Entity\Categories $categories
     *
     * @return ElementFormation
     */
    public function setCategories(\AppBundle\Entity\Categories $categories = null)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Get categories
     *
     * @return \AppBundle\Entity\Categories
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set affectations
     *
     * @param \AppBundle\Entity\Affectations $affectations
     *
     * @return ElementFormation
     */
    public function setAffectations(\AppBundle\Entity\Affectations $affectations = null)
    {
        $this->affectations = $affectations;

        return $this;
    }

    /**
     * Get affectations
     *
     * @return \AppBundle\Entity\Affectations
     */
    public function getAffectations()
    {
        return $this->affectations;
    }

    /**
     * Set resultats
     *
     * @param \AppBundle\Entity\Resultats $resultats
     *
     * @return ElementFormation
     */
    public function setResultats(\AppBundle\Entity\Resultats $resultats = null)
    {
        $this->resultats = $resultats;

        return $this;
    }

    /**
     * Get resultats
     *
     * @return \AppBundle\Entity\Resultats
     */
    public function getResultats()
    {
        return $this->resultats;
    }

    /**
     * Set cursus
     *
     * @param \AppBundle\Entity\Cursus $cursus
     *
     * @return ElementFormation
     */
    public function setCursus(\AppBundle\Entity\Cursus $cursus = null)
    {
        $this->cursus = $cursus;

        return $this;
    }

    /**
     * Get cursus
     *
     * @return \AppBundle\Entity\Cursus
     */
    public function getCursus()
    {
        return $this->cursus;
    }
}

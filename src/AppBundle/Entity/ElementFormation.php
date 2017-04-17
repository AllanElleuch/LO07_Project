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
    private $credit;

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
     * @ORM\ManyToOne(targetEntity="Affectations", inversedBy="elementsFormation")
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
     * @ORM\ManyToOne(targetEntity="Cursus", inversedBy="elementsFormations")
     * @ORM\JoinColumn(name="cursus_id", referencedColumnName="id")
     */
    private $cursus;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return ElementFormation
     */
    public function setLabel($label) {
        $this->label = $label;
        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel() {
        return $this->label;
    }

    /**
     * Set cursus
     *
     * @param \AppBundle\Entity\Cursus $cursus
     * @return ElementFormation
     */
    public function setCursus(\AppBundle\Entity\Cursus $cursus = null) {
        $this->cursus = $cursus;
        return $this;
    }

    /**
     * Get cursus
     *
     * @return \AppBundle\Entity\Cursus
     */
    public function getCursus() {
        return $this->cursus;
    }

    /**
     * Set semSeq
     *
     * @param \integer $semSeq
     *
     * @return ElementFormation
     */
    public function setSemSeq(\integer $semSeq)
    {
        $this->sem_seq = $semSeq;

        return $this;
    }

    /**
     * Get semSeq
     *
     * @return \integer
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
     * Set credit
     *
     * @param \integer $credit
     *
     * @return ElementFormation
     */
    public function setCredit(\integer $credit)
    {
        $this->credit = $credit;

        return $this;
    }

    /**
     * Get credit
     *
     * @return \integer
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     * Set categories
     *
     * @param integer $categories
     *
     * @return ElementFormation
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Get categories
     *
     * @return integer
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set affectations
     *
     * @param integer $affectations
     *
     * @return ElementFormation
     */
    public function setAffectations($affectations)
    {
        $this->affectations = $affectations;

        return $this;
    }

    /**
     * Get affectations
     *
     * @return integer
     */
    public function getAffectations()
    {
        return $this->affectations;
    }

    /**
     * Set resultats
     *
     * @param integer $resultats
     *
     * @return ElementFormation
     */
    public function setResultats($resultats)
    {
        $this->resultats = $resultats;

        return $this;
    }

    /**
     * Get resultats
     *
     * @return integer
     */
    public function getResultats()
    {
        return $this->resultats;
    }
}

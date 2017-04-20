<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="cursus")
 */
class Cursus
{


    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * Un cursus à plusieurs éléments de formations.
     * @ORM\OneToMany(targetEntity="ElementFormation", mappedBy="cursus",cascade={"all"})
     */
    private $elementsFormations;


    /**
     * Plusieurs cursust un seul étudiant.
     * @ORM\ManyToOne(targetEntity="Etudiants", inversedBy="cursus")
     * @ORM\JoinColumn(name="etudiants_id", referencedColumnName="id")
     */
    private $etudiant;

    public function __construct() {
        $this->elementsFormations = new ArrayCollection();
    }

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $label;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set elementsFormations
     *
     * @param array $elementsFormations
     * @return Cursus
     */
    public function setelementsFormations($elementsFormations) {
        $this->elementsFormations = $elementsFormations;
        return $this;
    }

    /**
     * Get elementsFormations
     *
     * @return array
     */
    public function getelementsFormations() {
        return $this->elementsFormations;
    }

    /**
     * Set label
     *
     * @param string $label
     * @return Cursus
     */
    public function setLabel($label){
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
     * Add elementsFormation
     *
     * @param \AppBundle\Entity\ElementFormation $elementsFormation
     * @return Cursus
     */
    public function addElementsFormation(\AppBundle\Entity\ElementFormation $elementsFormation) {
        $this->elementsFormations[] = $elementsFormation;
        return $this;
    }

    /**
     * Remove elementsFormation
     *
     * @param \AppBundle\Entity\ElementFormation $elementsFormation
     */
    public function removeElementsFormation(\AppBundle\Entity\ElementFormation $elementsFormation) {
        $this->elementsFormations->removeElement($elementsFormation);
    }

    /**
     * Set etudiant
     *
     * @param \AppBundle\Entity\Etudiants $etudiant
     *
     * @return Cursus
     */
    public function setEtudiant(\AppBundle\Entity\Etudiants $etudiant = null)
    {
        $this->etudiant = $etudiant;

        return $this;
    }

    /**
     * Get etudiant
     *
     * @return \AppBundle\Entity\Etudiants
     */
    public function getEtudiant()
    {
        return $this->etudiant;
    }
}

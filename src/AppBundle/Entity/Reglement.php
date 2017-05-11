<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="reglement")
 */
class Reglement
{


    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $label;

    /**
     * Un règlemet est composé de règles
     * @ORM\OneToMany(targetEntity="Regle", mappedBy="reglement",cascade={"all"})
     */
    private $regles;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->regles = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add regle
     *
     * @param \AppBundle\Entity\Regle $regle
     *
     * @return Reglement
     */
    public function addRegle(\AppBundle\Entity\Regle $regle)
    {
        $this->regles[] = $regle;

        return $this;
    }

    /**
     * Remove regle
     *
     * @param \AppBundle\Entity\Regle $regle
     */
    public function removeRegle(\AppBundle\Entity\Regle $regle)
    {
        $this->regles->removeElement($regle);
    }

    /**
     * Get regles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRegles()
    {
        return $this->regles;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return Reglement
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }
}

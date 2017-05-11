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
     * Un cursus à plusieurs éléments de formations.
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
}

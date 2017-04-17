<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="affectations")
 */
class Affectations
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
     * Une affectation à plusieurs elements de formations
     * @ORM\OneToMany(targetEntity="ElementFormation", mappedBy="affectations")
     */
    private $elementsFormation;


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
     * Set label
     *
     * @param string $label
     *
     * @return Affectations
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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->elementsFormation = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add elementsFormation
     *
     * @param \AppBundle\Entity\ElementFormation $elementsFormation
     *
     * @return Affectations
     */
    public function addElementsFormation(\AppBundle\Entity\ElementFormation $elementsFormation)
    {
        $this->elementsFormation[] = $elementsFormation;

        return $this;
    }

    /**
     * Remove elementsFormation
     *
     * @param \AppBundle\Entity\ElementFormation $elementsFormation
     */
    public function removeElementsFormation(\AppBundle\Entity\ElementFormation $elementsFormation)
    {
        $this->elementsFormation->removeElement($elementsFormation);
    }

    /**
     * Get elementsFormation
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getElementsFormation()
    {
        return $this->elementsFormation;
    }
}

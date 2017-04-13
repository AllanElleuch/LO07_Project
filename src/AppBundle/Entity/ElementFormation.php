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
* @ORM\Column(type="string", length=100)
*/
  private $label;

  /**
 * Plusieurs élément de formations on un seul cursus.
 * @ORM\ManyToOne(targetEntity="Cursus", inversedBy="elementsFormations")
 * @ORM\JoinColumn(name="cursus_id", referencedColumnName="id")
 */
private $cursus;


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
     * @return ElementFormation
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

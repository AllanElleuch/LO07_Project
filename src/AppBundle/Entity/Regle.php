<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="regle")
 */
class Regle
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
    private $seuil;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $cibleAgregat;



    /**
     * $affectations :  TC, TCBR, FLBR
     * plusieurs éléments de fomation on une affectations
     * @ORM\ManyToOne(targetEntity="Affectations")
     * @ORM\JoinColumn(name="affectations_id", referencedColumnName="id")
     */
    private $affectations;

    /**
     * Agregat :  SUM, EXIST
     * plusieurs règle on un agregat
     * @ORM\ManyToOne(targetEntity="Affectations")
     * @ORM\JoinColumn(name="affectations_id", referencedColumnName="id")
     */
    private $agregat;

    /**
     * Plusieurs élément de formations appartiennent un seul cursus.
     * @ORM\ManyToOne(targetEntity="Reglement", inversedBy="regles",cascade={"all"})
     * @ORM\JoinColumn(name="reglement_id", referencedColumnName="id")
     */
    private $reglement;



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
     * Set seuil
     *
     * @param integer $seuil
     *
     * @return Regle
     */
    public function setSeuil($seuil)
    {
        $this->seuil = $seuil;

        return $this;
    }

    /**
     * Get seuil
     *
     * @return integer
     */
    public function getSeuil()
    {
        return $this->seuil;
    }

    /**
     * Set cibleAgregat
     *
     * @param string $cibleAgregat
     *
     * @return Regle
     */
    public function setCibleAgregat($cibleAgregat)
    {
        $this->cibleAgregat = $cibleAgregat;

        return $this;
    }

    /**
     * Get cibleAgregat
     *
     * @return string
     */
    public function getCibleAgregat()
    {
        return $this->cibleAgregat;
    }

    /**
     * Set affectations
     *
     * @param \AppBundle\Entity\Affectations $affectations
     *
     * @return Regle
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
     * Set agregat
     *
     * @param \AppBundle\Entity\Affectations $agregat
     *
     * @return Regle
     */
    public function setAgregat(\AppBundle\Entity\Affectations $agregat = null)
    {
        $this->agregat = $agregat;

        return $this;
    }

    /**
     * Get agregat
     *
     * @return \AppBundle\Entity\Affectations
     */
    public function getAgregat()
    {
        return $this->agregat;
    }

    /**
     * Set reglement
     *
     * @param \AppBundle\Entity\Reglement $reglement
     *
     * @return Regle
     */
    public function setReglement(\AppBundle\Entity\Reglement $reglement = null)
    {
        $this->reglement = $reglement;

        return $this;
    }

    /**
     * Get reglement
     *
     * @return \AppBundle\Entity\Reglement
     */
    public function getReglement()
    {
        return $this->reglement;
    }
}

<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="etudiants")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class Etudiants
{
  /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * Un étudiant à plusieurs cursus
     * @ORM\OneToMany(targetEntity="Cursus", mappedBy="etudiant")
     */
    private $cursus;

    /**
     * filieres :  MPL, MSI, MRI, LIB, ?
     * plusieurs etudiants ont la même filieres
     * @ORM\ManyToOne(targetEntity="Filieres", inversedBy="etudiants")
     * @ORM\JoinColumn(name="filieres_id", referencedColumnName="id")
     */
    private $filieres;


    /**
     * admissions :  TC, BR
     *  plusieurs etudiants ont la même admissions
     * @ORM\ManyToOne(targetEntity="Admissions", inversedBy="etudiants")
     * @ORM\JoinColumn(name="admissions_id", referencedColumnName="id")
     */
    private $admissions;


    /**
     * @ORM\Column(type="string", length=64)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $prenom;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cursus = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return Etudiants
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Etudiants
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Etudiants
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Add cursus
     *
     * @param \AppBundle\Entity\Cursus $cursus
     *
     * @return Etudiants
     */
    public function addCursus(\AppBundle\Entity\Cursus $cursus)
    {
        $this->cursus[] = $cursus;

        return $this;
    }

    /**
     * Remove cursus
     *
     * @param \AppBundle\Entity\Cursus $cursus
     */
    public function removeCursus(\AppBundle\Entity\Cursus $cursus)
    {
        $this->cursus->removeElement($cursus);
    }

    /**
     * Get cursus
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCursus()
    {
        return $this->cursus;
    }

    /**
     * Set filieres
     *
     * @param \AppBundle\Entity\Filieres $filieres
     *
     * @return Etudiants
     */
    public function setFilieres(\AppBundle\Entity\Filieres $filieres = null)
    {
        $this->filieres = $filieres;

        return $this;
    }

    /**
     * Get filieres
     *
     * @return \AppBundle\Entity\Filieres
     */
    public function getFilieres()
    {
        return $this->filieres;
    }

    /**
     * Set admissions
     *
     * @param \AppBundle\Entity\Admissions $admissions
     *
     * @return Etudiants
     */
    public function setAdmissions(\AppBundle\Entity\Admissions $admissions = null)
    {
        $this->admissions = $admissions;

        return $this;
    }

    /**
     * Get admissions
     *
     * @return \AppBundle\Entity\Admissions
     */
    public function getAdmissions()
    {
        return $this->admissions;
    }
}

<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="etudiants")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class Etudiants  implements UserInterface, \Serializable
{
  /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
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
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    public function __construct()
    {
        $this->isActive = true;
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid(null, true));
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
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
     * Set password
     *
     * @param string $password
     *
     * @return Etudiants
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Etudiants
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Etudiants
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
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
}

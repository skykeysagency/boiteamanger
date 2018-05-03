<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * Groupe
 *
 * @ORM\Table(name="groupe")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GroupeRepository")
 */
class Groupe
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var int
     * @ORM\Column(name="proprietaire", type="integer", length=255)
     *
     */
    private $proprietaire;

    /**
     * @var User
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="groupe")
     */
    private $participant;

    /**
     * @var bool
     *
     * @ORM\Column(name="isPrivate", type="boolean")
     */
    private $isPrivate;


    /**
     * @var Plat[]
     *
     * @ORM\OneToMany(targetEntity="Plat", mappedBy="groupe", cascade={"remove"})
     */
    private $plat;

    /**
     * @return Plat[]
     */
    public function getPlat()
    {
        return $this->plat;
    }

    /**
     * @param Plat[] $plat
     */
    public function setPlat(array $plat)
    {
        $this->plat = $plat;
    }


    /**
     * Default constructor, initializes collections
     */
    public function __construct()
    {
        $this->participant = new ArrayCollection();
    }


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom.
     *
     * @param string $nom
     *
     * @return Groupe
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get nom.
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set proprietaire.
     *
     * @param User $proprietaire
     *
     * @return Groupe
     */
    public function setProprietaire($proprietaire)
    {
        $this->proprietaire = $proprietaire;
    
        return $this;
    }

    /**
     * Get proprietaire.
     *
     * @return int
     */
    public function getProprietaire()
    {
        return $this->proprietaire;
    }

    /**
     * Set participant.
     *
     * @param User $participant
     *
     * @return Groupe
     */
    public function setParticipant($participant)
    {
        $participant->setGroupe($this);
        $this->participant = $participant;

        return $this;
    }

    /**
     * Get participant.
     *
     * @return User
     */
    public function getParticipant()
    {
        return $this->participant;
    }

    /**
     * Set isPrivate.
     *
     * @param bool $isPrivate
     *
     * @return Groupe
     */
    public function setIsPrivate($isPrivate)
    {
        $this->isPrivate = $isPrivate;
    
        return $this;
    }

    /**
     * Get isPrivate.
     *
     * @return bool
     */
    public function getIsPrivate()
    {
        return $this->isPrivate;
    }

    /**
     * @param User $user
     */
    public function addUser(User $user)
    {
        if ($this->participant->contains($user)) {
            return;
        }
        $this->participant->add($user);
        $user->addUserGroup($this);
    }
    /**
     * @param User $user
     */
    public function removeUser(User $user)
    {
        if (!$this->participant->contains($user)) {
            return;
        }
        $this->participant->removeElement($user);
        $user->removeUserGroup($this);
    }

}

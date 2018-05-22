<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


/**
 * Plat
 *
 * @ORM\Table(name="plat")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlatRepository")
 */
class Plat
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var string
     *
     * @ORM\Column(name="nomPlat", type="string", length=255)
     */
    private $nomPlat;


    /**
     * @ManyToMany(targetEntity="Categorie", inversedBy="plat")
     * @JoinTable(name="plat_categorie",
     *     joinColumns={@JoinColumn(name="plat_id", referencedColumnName="id")},
     *     inverseJoinColumns={@JoinColumn(name="cat_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     */
    private $categorie;
   /**
     * @ORM\ManyToOne(targetEntity="Livraison", inversedBy="plat")
     * @JoinTable(name="plat_livraison",
     *     joinColumns={@JoinColumn(name="plat_id", referencedColumnName="id")},
     *     inverseJoinColumns={@JoinColumn(name="liv_id", referencedColumnName="id")}
     * )
     */
    private $livraison;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptionPlat", type="string", length=500)
     */
    private $descriptionPlat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dureeValide", type="datetime")
     */
    private $dureeValide;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creeA", type="datetime")
     */


    private $creeA;

    /**
     * @var prix
     *
     * @ORM\Column(name="prix", type="integer", nullable=true)
     */

    private $prix;


    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Ajouter une image jpg")
     * @Assert\File(mimeTypes={ "image/jpeg" })
     */
    private $imagePlat;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Commentaire",mappedBy="plat")
     */
    private $commentaires;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="plats")
     *
     */
    private $users;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User",inversedBy="platsPoste")
     * @ORM\JoinColumn(name="user_id",referencedColumnName="id")
     */
    private $userPoste;

    /**
     * @var Groupe
     * @ORM\ManyToOne(targetEntity="Groupe", inversedBy="plat")
     * @ORM\JoinTable(name="plat_groupe",
     *     joinColumns={@ORM\JoinColumn(name="plat_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="groupe_id", referencedColumnName="id")}
     * )
     */
    private $groupe;

    /**
     * @OneToOne(targetEntity="Reservation", mappedBy="plat")
     */
    private $reservation;

    /**
     * @return mixed
     */
    public function getReservation()
    {
        return $this->reservation;
    }

    /**
     * @param mixed $reservation
     */
    public function setReservation($reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * @return Groupe
     */
    public function getGroupe()
    {
        return $this->groupe;
    }

    /**
     * @param Groupe $groupe
     */
    public function setGroupe(Groupe $groupe)
    {
        $this->groupe = $groupe;
    }


    public function __construct()
    {
        $this->users = new ArrayCollection();

        $this->dureeValide = (new \DateTime('now'))->modify('+1 days');
        $this->creeA = (new \DateTime('now'));

        $this->commentaires = new ArrayCollection();

        return $this->getUserPoste();
        // TODO: Implement __toString() method.
    }

    /**
     * @return \AppBundle\Entity\User
     */
    public function getUserPoste()
    {
        return $this->userPoste;
    }

    /**
     * @param \AppBundle\Entity\User $userPoste
     */
    public function setUserPoste($userPoste)
    {
        $this->userPoste = $userPoste;
    }

    /**
     * @return mixed
     */
    public function getImagePlat()
    {
        return $this->imagePlat;
    }

    /**
     * @return ArrayCollection|Plat[]
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }

    /**
     */
    public function setCommentaires($commentaires)
    {
        $this->commentaires[] = $commentaires;
    }

    /**
     * @param mixed $imagePlat
     */
    public function setImagePlat($imagePlat)
    {
        $this->imagePlat = $imagePlat;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idPlat
     *
     * @param integer $idPlat
     *
     * @return Plat
     */
    public function setIdPlat($idPlat)
    {
        $this->idPlat = $idPlat;

        return $this;
    }

    /**
     * Get idPlat
     *
     * @return int
     */
    public function getIdPlat()
    {
        return $this->idPlat;
    }

    /**
     * Set nomPlat
     *
     * @param string $nomPlat
     *
     * @return Plat
     */
    public function setNomPlat($nomPlat)
    {
        $this->nomPlat = $nomPlat;

        return $this;
    }

    /**
     * Get nomPlat
     *
     * @return string
     */
    public function getNomPlat()
    {
        return $this->nomPlat;
    }

    /**
     * Set categorie
     *
     * @param string $categorie
     *
     * @return Plat
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return string
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set descriptionPlat
     *
     * @param string $descriptionPlat
     *
     * @return Plat
     */
    public function setDescriptionPlat($descriptionPlat)
    {
        $this->descriptionPlat = $descriptionPlat;

        return $this;
    }

    /**
     * Get descriptionPlat
     *
     * @return string
     */
    public function getDescriptionPlat()
    {
        return $this->descriptionPlat;
    }

    /**
     * Set dureeValide
     *
     * @param \DateTime $dureeValide
     *
     * @return Plat
     */
    public function setDureeValide($dureeValide)
    {
        $this->dureeValide = $dureeValide;

        return $this;
    }

    /**
     * Get dureeValide
     *
     * @return \DateTime
     */
    public function getDureeValide()
    {
        return $this->dureeValide;
    }

    /**
     * Set creeA
     *
     * @param \DateTime $creeA
     *
     * @return Plat
     */
    public function setCreeA($creeA)
    {
        $this->creeA = $creeA;

        return $this;
    }

    /**
     * Get creeA
     *
     * @return \DateTime
     */
    public function getCreeA()
    {
        return $this->creeA;
    }

    public function addUser(User $user)
    {
        if ($this->users->contains($user)) {
            return;
        }
        $this->users[] = $user;

    }

    /**
     * @return ArrayCollection|Plat[]
     */
    public function getUsers()
    {
        return $this->users;
    }

    public function removeUser(User $user)
    {
        if ($this->users->contains($user)) {
            return $this->users->removeElement($user);
        } else {
            return;
        }
    }

    /**
     * Set prix
     *
     * @param integer $prix
     *
     * @return Plat
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return integer
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Add commentaire
     *
     * @param \AppBundle\Entity\Commentaire $commentaire
     *
     * @return Plat
     */
    public function addCommentaire(\AppBundle\Entity\Commentaire $commentaire)
    {
        $this->commentaires[] = $commentaire;

        return $this;
    }

    /**
     * Remove commentaire
     *
     * @param \AppBundle\Entity\Commentaire $commentaire
     */
    public function removeCommentaire(\AppBundle\Entity\Commentaire $commentaire)
    {
        $this->commentaires->removeElement($commentaire);
    }



    /**
     * Add categorie.
     *
     * @param \AppBundle\Entity\Categorie $categorie
     *
     * @return Plat
     */
    public function addCategorie(\AppBundle\Entity\Categorie $categorie)
    {
        $this->categorie[] = $categorie;

        return $this;
    }

    /**
     * Remove categorie.
     *
     * @param \AppBundle\Entity\Categorie $categorie
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeCategorie(\AppBundle\Entity\Categorie $categorie)
    {
        return $this->categorie->removeElement($categorie);
    }

    /**
     * Set livraison.
     *
     * @param \AppBundle\Entity\Livraison|null $livraison
     *
     * @return Plat
     */
    public function setLivraison(\AppBundle\Entity\Livraison $livraison = null)
    {
        $this->livraison = $livraison;

        return $this;
    }

    /**
     * Get livraison.
     *
     * @return \AppBundle\Entity\Livraison|null
     */
    public function getLivraison()
    {
        return $this->livraison;
    }
}

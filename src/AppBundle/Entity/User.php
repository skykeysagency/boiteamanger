<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;
use FOS\UserBundle\Model\User as FOSUser;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Plat;


/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends FOSUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="facebook_id", type="string", length=255, nullable=true)
     */
    private $facebookID;

    /**
     * @ORM\Column(name="google_id", type="string", length=255, nullable=true)
     */
    private $googleID;

    private $facebookAccessToken;
    /**
     * @ORM\Column(type="string", length=20)
     */

    protected $nom;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $prenom;


    /**
     * @ORM\Column(type="datetime")
     */
    protected $dnaiss;

    /**
     * @ORM\Column(type="integer")
     */
    protected $genre;

    /**
     * @ORM\Column(type="string", length=14)
     */
    protected $tel;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Plat",mappedBy="userPoste")
     */
    private $platsPoste;


    /**
     * @ORM\Column(type="text")
     *
     * @Assert\NotBlank(message="Ajouter une image jpg")
     * @Assert\File(mimeTypes={ "image/jpeg" })
     */
    private $imageUser;

    /**
     * @ORM\ManyToMany(targetEntity="Plat", mappedBy="users")
     *
     */
    protected $plats;

    /**
     *
     * @ORM\ManyToMany(targetEntity="Groupe", inversedBy="participant")
     * @ORM\JoinTable(name="user_groupe",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="groupe_id", referencedColumnName="id")}
     * )
     *
     */
    protected $groupe;


    /**
     *
     *
     * @OneToMany(targetEntity="Reservation", mappedBy="vendeur")
     *
     */
    protected $reservVendeur;

    /**
     *
     *
     * @OneToMany(targetEntity="Reservation", mappedBy="acheteur")
     *
     */
    protected $reservAcheteur;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $noteTot;

    /**
     * @return int
     */
    public function getNoteTot()
    {
        return $this->noteTot;
    }

    /**
     * @param int $noteTot
     */
    public function setNoteTot($noteTot)
    {
        $this->noteTot = $noteTot;
    }

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    protected $noteMoyenne;

    /**
     * @return float
     */
    public function getNoteMoyenne()
    {
        return $this->noteMoyenne;
    }

    /**
     * @param float $noteMoyenne
     */
    public function setNoteMoyenne($noteMoyenne)
    {
        $this->noteMoyenne = $noteMoyenne;
    }

    /**
     *
     *
     * @OneToMany(targetEntity="Commentaire", mappedBy="auteur")
     *
     */
    protected $commentaires;

    /**
     * @return mixed
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }

    /**
     * @param mixed $commentaires
     */
    public function setCommentaires($commentaires)
    {
        $this->commentaires = $commentaires;
    }

    public function __construct()
    {
        parent::__construct();
        $this->plats = new ArrayCollection();

        $this->platsPoste = new ArrayCollection();

        $this->groupe = new ArrayCollection();
    }

    /**
     * @return ArrayCollection|User[]
     */
    public function getPlatsPoste()
    {
        return $this->platsPoste;
    }

    /**
     * @param mixed $platsPoste
     */
    public function setPlatsPoste($platsPoste)
    {
        $this->platsPoste[] = $platsPoste;
    }

    /**
     * @return mixed
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * @param mixed $tel
     */
    public function setTel($tel)
    {
        $this->tel = $tel;
    }

    /**
     * @return mixed
     */
    public function getImageUser()
    {
        return $this->imageUser;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $imageUser
     */
    public function setImageUser($imageUser)
    {
        $this->imageUser = $imageUser;
    }


    public function getNom()
    {
        return $this->nom;
    }

    public function getPrenom()
    {
        return $this->prenom;
    }

    public function getDnaiss()
    {
        return $this->dnaiss;
    }

    public function getGenre()
    {
        return $this->genre;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    public function setDnaiss($dnaiss)
    {
        $this->dnaiss = $dnaiss;
    }

    public function setGenre($genre)
    {
        $this->genre = $genre;
    }

    public function setEmail($email)
    {
        $email = is_null($email) ? '' : $email;
        parent::setEmail($email);
        $this->setUsername($email);

        return $this;
    }


    public function addPlat(Plat $plat)
    {
        if ($this->plats->contains($plat)) {
            return;
        }
        $this->plats[] = $plat;

    }

    /**
     * @return ArrayCollection|Plat[]
     */
    public function getPlats()
    {
        return $this->plats;
    }

    public function removePlat(Plat $plat)
    {
        if ($this->plats->contains($plat)) {
            return $this->plats->removeElement($plat);
        } else {
            return;
        }
    }

    /**
     * @param string $facebookID
     * @return User
     */
    public function setFacebookID($facebookID)
    {
        $this->facebookID = $facebookID;

        return $this;
    }

    /**
     * @return string
     */
    public function getFacebookID()
    {
        return $this->facebookID;
    }

    /**
     * @param string $facebookAccessToken
     * @return User
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebookAccessToken = $facebookAccessToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getFacebookAccessToken()
    {
        return $this->facebookAccessToken;
    }



    /**
     * Set googleID
     *
     * @param string $googleID
     *
     * @return User
     */
    public function setGoogleID($googleID)
    {
        $this->googleID = $googleID;

        return $this;
    }

    /**
     * Get googleID
     *
     * @return string
     */
    public function getGoogleID()
    {
        return $this->googleID;
    }

    /**
     * Add platsPoste
     *
     * @param \AppBundle\Entity\Plat $platsPoste
     *
     * @return User
     */
    public function addPlatsPoste(\AppBundle\Entity\Plat $platsPoste)
    {
        $this->platsPoste[] = $platsPoste;

        return $this;
    }

    /**
     * Remove platsPoste
     *
     * @param \AppBundle\Entity\Plat $platsPoste
     */
    public function removePlatsPoste(\AppBundle\Entity\Plat $platsPoste)
    {
        $this->platsPoste->removeElement($platsPoste);
    }

    /**
     * Set groupe
     *
     * @param Groupe $groupe
     *
     * @return User
     */
    public function setGroupe($groupe)
    {
        $this->groupe = $groupe;

        return $this;
    }

    /**
     * Get groupe
     *
     * @return ArrayCollection|\Doctrine\Common\Collections\Collection|Groupe[]
     */
    public function getGroupe()
    {
        return $this->groupe;
    }


    /**
     * @param Groupe $Group
     */
    public function addUserGroup(Groupe $Group)
    {
        if ($this->groupe->contains($Group)) {
            return;
        }
        $this->groupe->add($Group);
        $Group->addUser($this);
    }
    /**
     * @param Groupe $Group
     */
    public function removeUserGroup(Groupe $Group)
    {
        if (!$this->groupe->contains($Group)) {
            return;
        }
        $this->groupe->removeElement($Group);
        $Group->removeUser($this);
    }


}

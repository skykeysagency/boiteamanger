<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReservationRepository")
 */
class Reservation
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
     * @ManyToOne(targetEntity="User", inversedBy="reservVendeur")
     * @JoinColumn(name="vendeur_id", referencedColumnName="id")
     *
     */
    private $vendeur;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="reservAcheteur")
     * @JoinColumn(name="acheteur_id", referencedColumnName="id")
     *
     */
    private $acheteur;

    /**
     * @var int
     *
     * @ORM\Column(name="num", type="integer")
     */
    private $num;

    /**
     * Reservation constructor.
     */
    public function __construct()
    {
        $this->isClosed = false;
        $this->date = (new \DateTime('now'))->modify('+10 minutes');
    }

    /**
     * @return int
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * @param int $num
     */
    public function setNum($num)
    {
        $this->num = $num;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;

    /**
     * @var int
     *
     * @ORM\Column(name="cp", type="integer")
     */
    private $cp;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255)
     */
    private $ville;

    /**
     * @OneToOne(targetEntity="Plat", inversedBy="reservation")
     * @JoinColumn(name="plat_id", referencedColumnName="id", onDelete="cascade")
     */
    private $plat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_closed", type="boolean")
     */
    private $isClosed;

    /**
     * @return bool
     */
    public function isClosed()
    {
        return $this->isClosed;
    }

    /**
     * @param bool $isClosed
     */
    public function setIsClosed($isClosed)
    {
        $this->isClosed = $isClosed;
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
     * Set vendeur.
     *
     * @param User $vendeur
     *
     * @return Reservation
     */
    public function setVendeur($vendeur)
    {
        $this->vendeur = $vendeur;
    
        return $this;
    }

    /**
     * Get vendeur.
     *
     * @return User
     */
    public function getVendeur()
    {
        return $this->vendeur;
    }

    /**
     * Set acheteur.
     *
     * @param User $acheteur
     *
     * @return Reservation
     */
    public function setAcheteur($acheteur)
    {
        $this->acheteur = $acheteur;
    
        return $this;
    }

    /**
     * Get acheteur.
     *
     * @return User
     */
    public function getAcheteur()
    {
        return $this->acheteur;
    }

    /**
     * Set adresse.
     *
     * @param string $adresse
     *
     * @return Reservation
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    
        return $this;
    }

    /**
     * Get adresse.
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set cp.
     *
     * @param int $cp
     *
     * @return Reservation
     */
    public function setCp($cp)
    {
        $this->cp = $cp;
    
        return $this;
    }

    /**
     * Get cp.
     *
     * @return int
     */
    public function getCp()
    {
        return $this->cp;
    }

    /**
     * Set ville.
     *
     * @param string $ville
     *
     * @return Reservation
     */
    public function setVille($ville)
    {
        $this->ville = $ville;
    
        return $this;
    }

    /**
     * Get ville.
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set plat.
     *
     * @param Plat $plat
     *
     * @return Reservation
     */
    public function setPlat($plat)
    {
        $this->plat = $plat;
    
        return $this;
    }

    /**
     * Get plat.
     *
     * @return Plat
     */
    public function getPlat()
    {
        return $this->plat;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return Reservation
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
}

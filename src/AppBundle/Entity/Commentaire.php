<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commentaire
 *
 * @ORM\Table(name="commentaire")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommentaireRepository")
 */
class Commentaire
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
     * @ORM\Column(name="contenu", type="string", length=500)
     */
    private $contenu;

    /**
     * @var Plat
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Plat",inversedBy="commentaire")
     * @ORM\JoinColumn(name="plat_id",referencedColumnName="id")
     */
    private $plat;

    public function __construct()
    {
        return $this->getPlat();
        // TODO: Implement __toString() method.
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
     * Set contenu
     *
     * @param string $contenu
     *
     * @return Commentaire
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * @return Plat
     */
    public function getPlat()
    {
        return $this->plat;
    }

    /**
     * @param Plat $plat
     */
    public function setPlat($plat)
    {
        $this->plat = $plat;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }
}


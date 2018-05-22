<?php
/**
 * Created by PhpStorm.
 * User: macdedylan
 * Date: 04/05/2018
 * Time: 01:01
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Livraison
 *
 * @ORM\Table(name="livraison")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LivraisonRepository")
 */
class Livraison
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
     * @ORM\Column(name="cp", type="string")
     */
    private $cp;



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
     * Set cp.
     *
     * @param int $cp
     *
     * @return Livraison
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
}

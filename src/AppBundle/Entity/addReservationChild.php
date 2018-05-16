<?php
/**
 * Created by PhpStorm.
 * User: Mehdi
 * Date: 14/05/2018
 * Time: 15:05
 */

namespace AppBundle\Entity;

use AppBundle\Entity\Plat;

/**
 *
 * Class addReservationChild
 * @package AppBundle\Entity
 */
class addReservationChild
{
    /**
     * Step 1: plat
     *
     * @var Plat
     */
    protected $plat;

    /**
     * Step 2: info sur la reservation
     *
     * @var Reservation
     */
    protected $reservation;

    /**
     * addReservationChild constructor.
     */
    public function __construct()
    {
        $this->plat = new Plat();
        $this->reservation = new Reservation();
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
     * @return Reservation
     */
    public function getReservation()
    {
        return $this->reservation;
    }

    /**
     * @param Reservation $reservation
     */
    public function setReservation($reservation)
    {
        $this->reservation = $reservation;
    }



}
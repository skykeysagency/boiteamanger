<?php
/**
 * Created by PhpStorm.
 * User: Mehdi
 * Date: 11/05/2018
 * Time: 14:26
 */

namespace AppBundle\Form;


use Craue\FormFlowBundle\Form\FormFlow;
use Craue\FormFlowBundle\Form\FormFlowInterface;

class reservationFlow extends FormFlow
{
    protected function loadStepsConfig() {
        return array(
            array(
                'label' => 'Plat',
                'form_type' => 'AppBundle\Form\addStepNewPlat',
            ),
            array(
                'label' => 'Reservation',
                'form_type' => 'AppBundle\Form\addStepNewReservation',
            ),
            array(
                'label' => 'confirmation',
            ),
        );
    }

}
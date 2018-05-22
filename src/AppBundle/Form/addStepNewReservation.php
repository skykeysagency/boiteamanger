<?php
/**
 * Created by PhpStorm.
 * User: Mehdi
 * Date: 14/05/2018
 * Time: 16:11
 */

namespace AppBundle\Form;

use AppBundle\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class addStepNewReservation extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', \Symfony\Component\Form\Extension\Core\Type\TextType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Recherchez automatiquement l\'adresse'
            ])
            ->add('reservation', ReservationType::class, array(
                'data_class' => Reservation::class,
                'label' => 'Ou entrez votre adresse manuellement'
            ));

    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Mehdi
 * Date: 14/05/2018
 * Time: 15:33
 */

namespace AppBundle\Form;


use AppBundle\Entity\Plat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class addStepNewPlat extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plat', PlatType::class, array(
                'data_class' => Plat::class
            ));

    }
}
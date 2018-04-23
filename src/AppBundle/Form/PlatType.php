<?php

namespace AppBundle\Form;

use AppBundle\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class PlatType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nomPlat', TextType::class, array(
            'label' => 'Nom plat',
        ))
            ->add('categorie', EntityType::class, array(
                'class' => Categorie::class,
                'choice_label' => 'libelle',
                 'multiple' => true,
            ))
            ->add('descriptionPlat', TextType::class, array(
                'label' => 'Description plat',
            ))
            ->add('imagePlat', FileType::class, array(
                'label' => 'Image(JPG)',
                'data_class' => null,
            ));


    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Plat'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_plat';
    }


}

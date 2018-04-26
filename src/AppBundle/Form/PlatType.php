<?php

namespace AppBundle\Form;

use AppBundle\Entity\Categorie;
use AppBundle\Entity\Groupe;
use AppBundle\Entity\Plat;
use AppBundle\Entity\User;
use AppBundle\Repository\GroupeRepository;
use AppBundle\Repository\PlatRepository;
use AppBundle\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
            ))
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
                $plat = $event->getData();
                $userId = $plat->getUserPoste();

                $form = $event->getForm();

                    $form->add('groupe', EntityType::class, array(
                        'class' => Groupe::class,
                        'choice_label' => 'nom',
                        'query_builder' => function (GroupeRepository $ur) use ($userId) {
                            $a=$ur->findGroupeByUserId($userId);
                            return $a;

                        },
                        'label' => 'Attribuer un plat à un groupe : ',
                        'preferred_choices' => array('General'),
                ));
            });




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

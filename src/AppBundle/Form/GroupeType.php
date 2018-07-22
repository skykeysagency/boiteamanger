<?php

namespace AppBundle\Form;

use AppBundle\AppBundle;
use AppBundle\Entity\Groupe;
use AppBundle\Entity\Ingredient;
use AppBundle\Entity\Plat;
use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use FOS\UserBundle\Model\Group;
use PUGX\AutocompleterBundle\Form\Type\AutocompleteType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;use Symfony\Component\Form\Extension\Core\Type\FileType;

class GroupeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')

            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
                $groupe = $event->getData();
                $groupeId = $groupe->getId();
                $form = $event->getForm();
                $form->add('participant', EntityType::class, array(
                    'class' => User::class,
                    'query_builder' => function (UserRepository $ur) use ($groupeId) {
                       $subqueryBuilder = $ur->createQueryBuilder('p1');
                     $subquery = $subqueryBuilder->select('p1.id')
                            ->from('AppBundle:Groupe', 'g');
                        $subquery->andWhere(':groupeId MEMBER OF p1.groupe');
                      //main query
                        $builder = $ur->createQueryBuilder('p');
                     $query = $builder->select('p');
                     $query->andWhere($query->expr()->notIn('p.id', $subquery->getDQL()))
                            ->setParameter('groupeId', $groupeId);
                    $a=$query->getQuery();

                      return $query;
                    },
                    'label' => 'Ajouer des utilisateurs à un groupe : ',
                    'multiple' => true,
                )) ->add('imageGroup', FileType::class, array(
                    'label' => 'Image(JPG)',
                    'data_class' => null,
                ))
                ;
           });




    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Groupe'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_groupe';
    }


}

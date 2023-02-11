<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Guest;

class ReservationFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('guest', EntityType::class, [
                'class' => Guest::class,
                'label' => 'Gost',
                'choice_label' => 'email',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('sign_in_date', DateTimeType::class, [
                'label' => 'Datum prijave',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('sign_out_date', DateTimeType::class, [
                'label' => 'Datum odjave',
                'attr' => [
                    'class' => 'form-control'
                ]
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', Reservation::class);
    }
}

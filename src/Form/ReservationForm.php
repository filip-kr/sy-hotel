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

class ReservationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('guest', EntityType::class, [
                'class' => Guest::class,
                'label' => 'Gost*',
                'choice_label' => 'email',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('sign_in_date', DateTimeType::class, [
                'label' => 'Datum prijave*',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('sign_out_date', DateTimeType::class, [
                'label' => 'Datum odjave*',
                'attr' => [
                    'class' => 'form-control'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}

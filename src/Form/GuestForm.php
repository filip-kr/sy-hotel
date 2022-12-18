<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Guest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GuestForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Ime*',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Prezime*',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail*',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('dob', BirthdayType::class, [
                'label' => 'Datum rođenja*',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => 'Država*',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('oib', TextType::class, [
                'label' => 'OIB',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => false
            ])
            ->add('passportNumber', TextType::class, [
                'label' => 'Broj putovnice',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Guest::class,
        ]);
    }
}

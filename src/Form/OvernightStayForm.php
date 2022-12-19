<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\OvernightStay;
use App\Entity\Room;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class OvernightStayForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('room', EntityType::class, [
                'class' => Room::class,
                'label' => 'Soba*',
                'choice_label' => 'number',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('totalPrice', MoneyType::class, [
                'label' => 'Cijena ukupno',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('isActive', ChoiceType::class, [
                'label' => 'Aktivno',
                'required' => false,
                'choices' => [
                    'Da' => true,
                    'Ne' => false
                ],
                'expanded' => true,
                'multiple' => false,
                'placeholder' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OvernightStay::class,
        ]);
    }
}

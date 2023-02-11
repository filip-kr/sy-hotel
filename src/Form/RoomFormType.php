<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Room;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoomFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', NumberType::class, [
                'label' => 'Broj sobe *',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('numberOfBeds', NumberType::class, [
                'label' => 'Broj kreveta *',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('description', TextType::class, [
                'label' => 'Opis',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => false
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Cijena noćenja *',
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
        $resolver->setDefault('data_class', Room::class);
    }
}

<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\OvernightStay;
use App\Entity\Reservation;
use App\Entity\Room;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Repository\ReservationRepository;
use App\Repository\RoomRepository;

class OvernightStayFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reservation', EntityType::class, [
                'class' => Reservation::class,
                'label' => 'Rezervacija*',
                'choice_label' => 'guest.email',
                'attr' => [
                    'class' => 'form-control'
                ],
                'query_builder' => function (ReservationRepository $rr) {
                    return $rr->createQueryBuilder('r')
                        ->andWhere('r.overnightStay IS NULL');
                }
            ])
            ->add('room', EntityType::class, [
                'class' => Room::class,
                'label' => 'Soba*',
                'choice_label' => 'number',
                'attr' => [
                    'class' => 'form-control'
                ],
                'query_builder' => function (RoomRepository $rr) {
                    return $rr->createQueryBuilder('r')
                        ->select('r')
                        ->andWhere('

                            r NOT IN (SELECT IDENTITY(os.room) 
                            FROM App\Entity\OvernightStay os 
                            WHERE os.isActive = true)

                        ');
                }
            ])
            ->add('totalPrice', MoneyType::class, [
                'label' => 'Cijena ukupno*',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => false
            ])
            ->add('isActive', ChoiceType::class, [
                'label' => 'Aktivno*',
                'required' => false,
                'choices' => [
                    'Da' => true,
                    'Ne' => false
                ],
                'expanded' => true,
                'multiple' => false,
                'placeholder' => false,
                'data' => true
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', OvernightStay::class);
    }
}

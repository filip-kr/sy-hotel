<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\CallbackTransformer;

class RegistrationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $roles = [
            'Administrator' => 'ROLE_ADMIN',
            'Korisnik' => 'ROLE_USER'
        ];

        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Ime',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Prezime',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('email')
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'label' => 'Lozinka mora sadržavati najmanje 8 znakova',
                'invalid_message' => 'Lozinke se ne podudaraju',
                'required' => true,
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'first_options'  => ['label' => 'Lozinka'],
                'second_options' => ['label' => 'Ponovi lozinku'],
                'constraints' => [
                    new NotBlank([]),
                    new Length([
                        'min' => 8,
                        'max' => 4096,
                    ]),
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Uloga',
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'choices' => [
                    'Administrator' => 'ROLE_ADMIN',
                    'Korisnik' => 'ROLE_USER'
                ]
            ]);

        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    return count($rolesArray) ? $rolesArray[0] : null;
                },
                function ($rolesString) {
                    return [$rolesString];
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

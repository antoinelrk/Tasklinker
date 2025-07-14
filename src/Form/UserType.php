<?php

namespace App\Form;

use App\Entity\User;
use App\Enum\EmploymentContractEnum;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('first_name')
            ->add('email')
            ->add('employment_started_at', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('roles', ChoiceType::class)
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôle',
                'choices' => [
                    'Collaborateur' => 'ROLE_USER',
                    'Chef de projet' => 'ROLE_ADMIN',
                ],
                'multiple' => true,
            ])
            ->add('employment_contract', ChoiceType::class, [
                'choices' => [
                    EmploymentContractEnum::CDI->value => EmploymentContractEnum::CDI->value,
                    EmploymentContractEnum::CDD->value => EmploymentContractEnum::CDD->value,
                    EmploymentContractEnum::FREELANCE->value => EmploymentContractEnum::FREELANCE->value
                ],
            ]);
    }

    /**
     * Configure the options for the form.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

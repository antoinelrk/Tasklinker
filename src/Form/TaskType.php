<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\Task;
use App\Entity\User;
use App\Enum\TaskStateEnum;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('body')
            ->add('state', ChoiceType::class, [
                'choices' => [
                    'TODO' => 'TODO',
                    'DOING' => 'DOING',
                    'DONE' => 'DONE',
                ],
                'data' => $options['default_state']
            ]) // Select
            ->add('likely_end_at', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('User', EntityType::class, [
                'class' => User::class,
                'choice_label' => fn($user) => $user->getFullName(),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            'default_state' => TaskStateEnum::TODO->value,
        ]);
    }
}

<?php

namespace App\Form;

use App\Dto\TaskDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('title', TextType::class, [
            'row_attr' => ['class' => 'mb-3'],
            'attr' => ['class' => 'form-control'],
            'label_attr' => ['class' => 'form-label'],
            'constraints' => [new Length([
                'min' => 2,
                'minMessage' => 'Title should be at least {{ limit }} characters',
                'max' => 100,
                ])],
            ])
            ->add('description', TextareaType::class, [
                'row_attr' => ['class' => 'mb-3'],
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
                'constraints' => [new Length([
                    'min' => 2,
                    'minMessage' => 'Description should be at least {{ limit }} characters',
                    'max' => 300,
                ])],
            ])
            ->add('date', DateType::class, [
                'row_attr' => ['class' => 'mb-3'],
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
                'constraints' => [new Callback(function($object, ExecutionContextInterface $context) {
                    $date = $context->getRoot()->getData()->getDate();

                    if ($date < (new \DateTime())) {
                       $context
                           ->buildViolation('Date must be bigger than now date!')
                           ->addViolation();
                    }
                }),]
            ])
            ->add('status',  ChoiceType::class, [
                'choices' => [
                    'New' => TaskDto::STATUS_NEW,
                    'In progress' => TaskDto::STATUS_INPROGRESS,
                    'Done' => TaskDto::STATUS_DONE,
                ],
                'row_attr' => ['class' => 'mb-3'],
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label'],
            ])
        ;
    }
}
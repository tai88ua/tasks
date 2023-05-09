<?php

namespace App\Form;

use App\Dto\TaskDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CriteriaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sortASC', CheckboxType::class, [
                'row_attr' => ['class' => 'form-group col-2'],
                'attr' => ['class' => 'form-check-input'],
                'label_attr' => ['class' => 'form-check-label'],
                'label' => 'ASC:',
                'required' => false,
            ])
            ->add('filterByDate', DateType::class, [
                'row_attr' => ['class' => 'form-group col-4'],
                'attr' => ['class' => 'col-7'],
                'label_attr' => ['class' => 'col-5'],
                'label' => 'Start date:'
            ])
            ->add('sortedBy', ChoiceType::class, [
                'choices' => [
                    'default'   => 'id',
                    'Title'     => 'title',
                    'Date'      => 'date',
                ],
                'row_attr' => ['class' => 'form-group col-3'],
                'attr' => ['class' => 'col-6'],
                'label_attr' => ['class' => 'col-6'],
                'label' => 'sorted By:'
            ])
            ->add('filterByStatus', ChoiceType::class, [
                'choices' => [
                    'default'       => null,
                    'New'           => TaskDto::STATUS_NEW,
                    'In progress'   => TaskDto::STATUS_INPROGRESS,
                    'Done'          => TaskDto::STATUS_DONE,
                ],
                'row_attr' => ['class' => 'form-group col-2'],
                'attr' => ['class' => 'col-8'],
                'label_attr' => ['class' => 'col-4'],
                'label' => 'Filter:'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'attr' => ['class' => 'form-inline row'],
        ]);
    }
}

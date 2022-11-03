<?php

namespace App\Form;

use App\Entity\Offers;
use App\Entity\Skills;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;

class OffersFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options,): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('endDate');
        $builder->add('skills', ChoiceType::class, [
            'choices' => $options['attr'],
            'multiple' => true,
            'expanded' => true,
            'label' => 'Skills',
            'required' => true,
            'mapped' => false,
            'constraints' => [
                new Count([
                    'min' => 1,
                    'max' => 5,
                    'minMessage' => 'You must select at least {{ limit }} skills',
                    'maxMessage' => 'You must select at most {{ limit }} skills',
                ]),
            ],
        ]);
        $builder->add('submit', SubmitType::class, [
            'label' => 'Create',
            'attr' => [
                'class' => 'btn btn-primary'
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offers::class,
        ]);
    }
}

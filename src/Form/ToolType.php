<?php

namespace App\Form;

use App\Entity\Tool;
use App\Entity\Category;
use App\Entity\Department;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ToolType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('text',TextareaType::class, [
                'attr' => ['rows'=> '4', 'maxlength'=>'255'],
            ])
            ->add('picture', FileType::class, [
                'attr' => ['accept' =>'image/png, image/jpeg'],
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Le site accepte seulement les images aux format PNG ou JPEG',
                    ])
                ],
            ])
            ->add('prices')
            ->add('address')
            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'Offre' => 'Offre',
                    'Demande' => 'Demande',
                ],
            ])
            ->add('department', EntityType::class, [
                'class' => Department::class,
                'required' => true,
                'choice_label' => function (Department $department) {
                    return $department->getFullName();
                }
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'required' => true,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tool::class,
        ]);
    }
}

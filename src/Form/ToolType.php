<?php

namespace App\Form;

use App\Entity\Tool;
use App\Entity\Category;
use App\Entity\Department;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ToolType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('text',TextareaType::class, [
                'attr' => ['rows'=> '4'],
            ])
            ->add('picture', FileType::class, [
                'attr' => ['accept'=> 'image/png, image/jpeg']
            ])
            ->add('prices')
            ->add('address')
            ->add('department', EntityType::class, [
                'class' => Department::class,
                'required' => true,
                'choice_label' => 'name',
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

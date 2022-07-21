<?php

namespace App\Form;

use App\Entity\Pays;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PaysType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', CountryType::class)
            ->add('drapeau', FileType::class, [
                'mapped' => false,
                'constraints' => [
                    new File(
                        mimeTypes:["image/jpeg", "image/png"],
                        mimeTypesMessage: "Seuls les formats jpeg, jpg et png sont acceptés"
                    )
                    ],
                    'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Ajouter"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pays::class,
        ]);
    }
}

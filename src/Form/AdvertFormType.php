<?php

namespace App\Form;

use App\Entity\Advert;
use App\Entity\Category;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdvertFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [

                "attr" => [
                    "placeholder" => "Ventilateur sur pied"
                ],
                'required' => false,
                "constraints" => new NotBlank(['message' => "Le nom du produit ne doit pas être vide"])
            ])
            ->add('description',TextareaType::class, [

                "attr" => [

                    "placeholder" => "Décrivez en quelques lignes votre annonce"
                ]
            ])
            ->add('price', MoneyType::class, [

                "attr" => [

                    "placeholder" => "100 €"
                ]
            ])
            ->add('country', CountryType::class, [

                "attr" => [

                    "placeholder" => "41 BD Charles Moretti, 13014 Marseille"
                ]
            ])
            ->add('city', TextType::class, [

                "attr" => [

                    "placeholder" => "41 BD Charles Moretti, 13014 Marseille"
                ]
            ])
            ->add('zipcode', TextType::class, [

                "attr" => [

                    "placeholder" => "41 BD Charles Moretti, 13014 Marseille"
                ]
            ])
            ->add('imageFile', VichImageType::class, [
                "label" => "Télécharger une image"
            ])
            ->add('category', EntityType::class, [

                "label" => "Catégorie",
                'class' => Category::class,
                'choice_label' => function(Category $category) {

                    return strtoupper($category->getName());
                }
            ])
            ->add('submit', SubmitType::class, [
                "label" => "Publier",
                "attr" => [

                    "class" => "bg-green-500 text-white w-96 rounded-sm mt-3 px-4 py-2"
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Advert::class,
        ]);
    }
}

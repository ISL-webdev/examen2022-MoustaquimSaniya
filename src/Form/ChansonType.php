<?php

namespace App\Form;

use App\Entity\Chanson;
use App\Entity\Genre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChansonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class)
            ->add('nomAlbum', TextType::class)
            ->add('paroles', TextareaType::class)
            ->add('auteur', TextType::class)
            //je commente vote et dateAjout pour qu on en ajoute pas manuellement
           // ->add('votes')
           // ->add('dateAjout', DateTimeType::class)
            ->add('dateSortie', DateType::class)
            //pour choisir parmis les categories existantes
            ->add('genre', EntityType::class, [
                'class' => Genre::class,
                'choice_label' => 'nom',
                'multiple' => false,
                'expanded' => false
            ])
            //pour envoyer le formulaire
            ->add('Enregistrer', SubmitType::class )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chanson::class,
        ]);
    }
}

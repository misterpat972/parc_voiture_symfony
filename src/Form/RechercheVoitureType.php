<?php

namespace App\Form;

use App\Entity\RechercheVoiture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class RechercheVoitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('minAnnee', IntegerType::class, [
                //le champ n'est pas obligatoire
                'required' => false,
                //le label du champ
                'label' => "Année de :"                
            ])
            ->add('maxAnnee', IntegerType::class, [
                //le champ n'est pas obligatoire
                'required' => false,
                //le label du champ
                'label' => "Année à :"                
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RechercheVoiture::class,
        ]);
    }
}

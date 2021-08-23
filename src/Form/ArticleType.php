<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('price')
            ->add('published')
            ->add('toparticle')
            //je precise a symfony que ce champ est une entité et qu'il faudra choisir le titre de la category
            ->add('category',EntityType::class,[
                'class'=>Category::class,
                'choice_label'=> 'title'
            ])
                // je lui indique qu'il va resevoir un fichier de type file
            ->add('brochure', FileType::class, [
                'label' => 'Brochure',
                //je lui indique qu'il ne doit pas s'occuper du deplacement du fichier
                'mapped' => false,
            ])
            //et ici le bouton submit pour envoyer le formulaire
            ->add('submit', SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}

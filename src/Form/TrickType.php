<?php

namespace App\Form;

use App\Entity\Trick;
use App\Form\ImageType;
use App\Form\VideoType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TrickType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('date_created')
            ->add('description')
            ->add('groups', EntityType::class, array(
                'class'        => 'App\Entity\Group',
                'choice_label' => 'name',
                'multiple'     => true,
            ))
            ->add('images', CollectionType::class, array(
                'entry_type' => ImageType::class,
                'allow_delete' => true,
                'by_reference' => false,
            ))
            ->add('videos', CollectionType::class, array(
                'entry_type' => VideoType::class,
                'allow_delete' => true,
                'by_reference' => false,
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}

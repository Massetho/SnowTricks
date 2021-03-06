<?php
/**
 * @description : New trick form
 * @Author : Quentin Thomasset
 */

namespace App\Form;

use App\Entity\Trick;
use App\Form\ImageType;
use App\Form\VideoType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TrickType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'constraints' => array(new Length(array('min' => 4,
                    'max' => 120,
                    'minMessage' => "Trick name must be at least {{ limit }} characters long",
                    'maxMessage' => "Trick name cannot be longer than {{ limit }} characters")),
                    new NotBlank())
            ))
            ->add('description', TextareaType::class, array(
                'attr' => array('rows' => '15'),
                'constraints' => array(new Length(array('min' => 12,
                    'max' => 5000,
                    'minMessage' => "Trick description must be at least {{ limit }} characters long",
                    'maxMessage' => "Trick description cannot be longer than {{ limit }} characters")))
            ))
            ->add('groups', EntityType::class, array(
                'class'        => 'App\Entity\Group',
                'choice_label' => 'name',
                'multiple'     => true,
            ))
            ->add('topImage', ImageType::class)
            ->add('bottomImages', CollectionType::class, array(
                'entry_type' => ImageType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
            ))
            ->add('videos', CollectionType::class, array(
                'entry_type' => VideoType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
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

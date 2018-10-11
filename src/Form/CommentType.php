<?php
/**
 * @description : Comment form
 * @Author : Quentin Thomasset
 */

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content', TextareaType::class, array(
            'label' => false,
            'required' => true,
            'constraints' => array(new Length(array('min' => 4,
                'max' => 1000,
                'minMessage' => "Comments must be at least {{ limit }} characters long",
                'maxMessage' => "Comments cannot be longer than {{ limit }} characters")),
                new NotBlank())

        ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Comment::class,
        ));
    }
}

<?php
/**
 * @description : New User form (for registration)
 * @Author : Quentin Thomasset
 */

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array(
                'required' => true,
                'constraints' => array(new Length(array('min' => 4,
                    'max' => 254,
                    'maxMessage' => "Your email cannot be longer than {{ limit }} characters")),
                    new NotBlank(array(
                        'groups' => array('registration'))),
                    new Email(array(
                        'groups' => array('registration'))))))
            ->add('username', TextType::class, array(
                'required' => true,
                'constraints' => array(new Length(array('min' => 4,
                    'max' => 30,
                    'minMessage' => "Username must be at least {{ limit }} characters long",
                    'maxMessage' => "Username cannot be longer than {{ limit }} characters")),
                    new NotBlank(array(
                        'groups' => array('registration'))))
            ))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
                'constraints' => array(new Length(array('min' => 4,
                    'max' => 4000,
                    'minMessage' => "Password be at least {{ limit }} characters long",
                    'maxMessage' => "Password cannot be longer than {{ limit }} characters")),
                    new NotBlank(array(
                        'groups' => array('registration', 'reset_password'))))
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}

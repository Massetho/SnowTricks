<?php
/**
 * @description :
 * @package : PhpStorm.
 * @Author : quent
 * @date: 08/05/2018
 * @time: 11:54
 */
namespace App\Form;

use App\Entity\Image;
use App\Entity\TopImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class TopImageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('file', FileType::class,
            array('label' => 'Image(JPG)',
                'required' => false));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => TopImage::class,
        ));
    }
}
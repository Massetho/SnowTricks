<?php
/**
 * @description : Video form
 * @Author : Quentin Thomasset
 */

namespace App\Form;

use App\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class VideoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'url',
            UrlType::class,
            array('label' => 'Video\'s URL',
                'required' => false,
                'constraints' => array(new NotBlank(array('message' => 'Add a video URL')),
                    new Regex(array('pattern' => "#^(http|https)://(www.youtube.com|www.dailymotion.com|vimeo.com)/#",
                    'match' => true,
                    'message' => "The URL must be of a Youtube, DailyMotion or Vimeo video."))))
        );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Video::class,
        ));
    }
}

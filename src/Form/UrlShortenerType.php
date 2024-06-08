<?php

namespace App\Form;

use App\Entity\UrlShortener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Url;

class UrlShortenerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('longUrl', TextType::class, [
                'label' => 'Your Long URL',
                'attr' => [
                    'placeholder' => 'Enter your Long URL',
                    'title' => 'Enter your Long URL',
                ]
            ])
            ->add('save', SubmitType::class, ['label' => 'Create Short URL']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UrlShortener::class,
        ]);
    }
}

<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;

class ConservationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', TextType::class, array(
                  'attr'  => array(
                      'class' => 'md-form-control',
                      'autocomplete'  => 'off',
                      'spellcheck'  => "false",
                      'data-rule-minlength' =>  "3",
                      'data-msg-required' =>  "Veuillez entrer le nom de l'unité de conservation.",
                      'data-msg-minlength'  =>  "Veuillez entrer au moins 3 caractères.",
                  ),
                  'required'  => true,
            ))
            //->add('slug')->add('publiePar')->add('modifiePar')->add('publieLe')->add('modifieLe')
            ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Conservation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_conservation';
    }


}

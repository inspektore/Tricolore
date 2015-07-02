<?php
namespace Tricolore\Form\FormTypes\Backend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class AuthType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', 'text', [
                'constraints' => [
                    new Assert\NotBlank()
                ],
                'label' => false,
                'attr' => [
                    'placeholder' => $options['data']['translator']->trans('Email address or username'),
                    'class' => 'backend-auth-input'
                ]
            ])
            ->add('password', 'password', [
                'constraints' => [
                    new Assert\NotBlank()
                ],
                'label' => false,
                'attr' => [
                    'placeholder' => $options['data']['translator']->trans('Password'),
                    'class' => 'backend-auth-input'
                ]
            ])
            ->add('admincp_auth_submit', 'submit', [
                'label' => $options['data']['translator']->trans('Log in'),
                'attr' => [
                    'class' => 'btn btn-success backend-auth-button-continue full-width'
                ]
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'auth_backend';
    }
}

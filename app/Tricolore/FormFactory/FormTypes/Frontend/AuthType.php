<?php
namespace Tricolore\FormFactory\FormTypes\Frontend;

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
                'label' => $options['data']['translator']->trans('Login'),
                'attr' => [
                    'placeholder' => $options['data']['translator']->trans('Email address or username')
                ]
            ])
            ->add('password', 'password', [
                'constraints' => [
                    new Assert\NotBlank()
                ],
                'label' => $options['data']['translator']->trans('Password'),
                'attr' => [
                    'placeholder' => $options['data']['translator']->trans('Password')
                ]
            ])
            ->add('autologin', 'checkbox', [
                'label' => 'Remember me',
                'label_attr' => [
                    'for' => 'auth_frontend_autologin'
                ],
                'attr' => [
                    'class' => 'checkbox',
                ],
                'required' => false
            ])
            ->add('auth-submit', 'submit', [
                'label' => $options['data']['translator']->trans('Log in'),
                'attr' => [
                    'class' => 'btn-primary frontend-auth-button-continue full-width'
                ]
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'auth_frontend';
    }
}

<?php
namespace Tricolore\FormFactory\FormTypes\Frontend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text', [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length([
                        'min' => 3,
                        'max' => 35
                    ])
                ],
                'label' => $options['data']['translator']->trans('Username'),
                'attr' => [
                    'placeholder' => $options['data']['translator']->trans('Type your username')
                ]
            ])
            ->add('email', 'email', [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email()
                ],
                'label' => $options['data']['translator']->trans('E-mail'),
                'attr' => [
                    'placeholder' => $options['data']['translator']->trans('Type your active e-mail')
                ]
            ])
            ->add('password', 'password', [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length([
                        'min' => 6
                    ])
                ],
                'label' => $options['data']['translator']->trans('Password'),
                'attr' => [
                    'placeholder' => $options['data']['translator']->trans('Type your password')
                ]
            ])
            ->add('password-repeat', 'password', [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length([
                        'min' => 6
                    ])
                ],
                'label' => $options['data']['translator']->trans('Repeat password'),
                'attr' => [
                    'placeholder' => $options['data']['translator']->trans('Type your password')
                ]
            ])
            ->add('register-submit', 'submit', [
                'label' => $options['data']['translator']->trans('Register'),
                'attr' => [
                    'class' => 'btn-primary'
                ]
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'register_frontend';
    }
}

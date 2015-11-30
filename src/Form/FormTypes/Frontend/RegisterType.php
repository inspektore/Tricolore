<?php
namespace Tricolore\Form\FormTypes\Frontend;

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
            ->add('username', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
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
            ->add('email', 'Symfony\Component\Form\Extension\Core\Type\EmailType', [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email()
                ],
                'label' => $options['data']['translator']->trans('E-mail'),
                'attr' => [
                    'placeholder' => $options['data']['translator']->trans('Type your active e-mail')
                ]
            ])
            ->add('password', 'Symfony\Component\Form\Extension\Core\Type\PasswordType', [
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
            ->add('password_repeat', 'Symfony\Component\Form\Extension\Core\Type\PasswordType', [
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
            ->add('register_submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType', [
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

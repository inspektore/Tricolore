<?php
namespace Tricolore\Form\FormTypes\Installer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type as Type;

class InstallerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add($builder->create('general_settings', Type\FormType::class, [
                'label_attr' => [
                    'class' => 'installer-step-title'
                ], 'inherit_data' => true
            ]))
            ->add('general_url', Type\UrlType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ],
                'label' => $options['data']['translator']->trans('Full URL address (without trailing slash)'),
                'attr' => [
                    'class' => 'input-lg',
                    'placeholder' => $options['data']['translator']->trans('http://example.com')
                ]
            ])
            ->add($builder->create('database', Type\FormType::class, [
                'label_attr' => [
                    'class' => 'installer-step-title'
                ], 'inherit_data' => true
            ]))
            ->add('database_host', Type\TextType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ],
                'label' => $options['data']['translator']->trans('Database host'),
                'attr' => [
                    'class' => 'input-lg',
                    'placeholder' => $options['data']['translator']->trans('localhost')
                ]
            ])
            ->add('database_name', Type\TextType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ],
                'label' => $options['data']['translator']->trans('Database name'),
                'attr' => [
                    'class' => 'input-lg',
                    'placeholder' => $options['data']['translator']->trans('tricolore')
                ]
            ])
            ->add('database_test_name', Type\TextType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ],
                'label' => $options['data']['translator']->trans('Database for unit testing (optional)'),
                'attr' => [
                    'class' => 'input-lg',
                    'placeholder' => $options['data']['translator']->trans('tricolore_tests')
                ]
            ])
            ->add('database_username', Type\TextType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ],
                'label' => $options['data']['translator']->trans('Database username'),
                'attr' => [
                    'class' => 'input-lg',
                    'placeholder' => $options['data']['translator']->trans('PostgreSQL username')
                ]
            ])
            ->add('database_password', Type\PasswordType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ],
                'label' => $options['data']['translator']->trans('Database password'),
                'attr' => [
                    'class' => 'input-lg',
                    'placeholder' => $options['data']['translator']->trans('PostgreSQL password')
                ]
            ])
            ->add('database_prefix', Type\TextType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ],
                'label' => $options['data']['translator']->trans('Prefix for tables (optional)'),
                'attr' => [
                    'class' => 'input-lg',
                    'placeholder' => $options['data']['translator']->trans('tricolore_')
                ]
            ])
            ->add($builder->create('administrator_account', Type\FormType::class, [
                'label_attr' => [
                    'class' => 'installer-step-title'
                ], 'inherit_data' => true
            ]))
            ->add('admin_username', Type\TextType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ],
                'label' => $options['data']['translator']->trans('Username'),
                'attr' => [
                    'class' => 'input-lg',
                    'placeholder' => $options['data']['translator']->trans('Administrator username')
                ]
            ])
            ->add('admin_password', Type\PasswordType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ],
                'label' => $options['data']['translator']->trans('Password'),
                'attr' => [
                    'class' => 'input-lg',
                    'placeholder' => $options['data']['translator']->trans('Administrator password')
                ]
            ])
            ->add('admin_password_confirm', Type\PasswordType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ],
                'label' => $options['data']['translator']->trans('Confirm password'),
                'attr' => [
                    'class' => 'input-lg',
                    'placeholder' => $options['data']['translator']->trans('Confirm password')
                ]
            ])
            ->add('admin_email', Type\EmailType::class, [
                'constraints' => [
                    new Assert\NotBlank()
                ],
                'label' => $options['data']['translator']->trans('E-Mail'),
                'attr' => [
                    'class' => 'input-lg',
                    'placeholder' => $options['data']['translator']->trans('Administrator e-mail')
                ]
            ])
            ->add('auth_submit', Type\SubmitType::class, [
                'label' => $options['data']['translator']->trans('Continue installation'),
                'attr' => [
                    'class' => 'btn-default btn installer-step-button'
                ]
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'installer';
    }
}

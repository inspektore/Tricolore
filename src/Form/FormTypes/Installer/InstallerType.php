<?php
namespace Tricolore\Form\FormTypes\Installer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class InstallerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add($builder->create('general_settings', 'form', [
                'label_attr' => [
                    'class' => 'installer-step-title'
                ], 'virtual' => true
            ]))
            ->add('general_url', 'url', [
                'constraints' => [
                    new Assert\NotBlank()
                ],
                'label' => $options['data']['translator']->trans('Full URL address (without trailing slash)'),
                'attr' => [
                    'class' => 'input-lg',
                    'placeholder' => $options['data']['translator']->trans('http://example.com')
                ]
            ])
            ->add($builder->create('database', 'form', [
                'label_attr' => [
                    'class' => 'installer-step-title'
                ], 'virtual' => true
            ]))
            ->add('database_host', 'text', [
                'constraints' => [
                    new Assert\NotBlank()
                ],
                'label' => $options['data']['translator']->trans('Database host'),
                'attr' => [
                    'class' => 'input-lg',
                    'placeholder' => $options['data']['translator']->trans('localhost')
                ]
            ])
            ->add('database_name', 'text', [
                'constraints' => [
                    new Assert\NotBlank()
                ],
                'label' => $options['data']['translator']->trans('Database name'),
                'attr' => [
                    'class' => 'input-lg',
                    'placeholder' => $options['data']['translator']->trans('tricolore')
                ]
            ])
            ->add('database_test_name', 'text', [
                'constraints' => [
                    new Assert\NotBlank()
                ],
                'label' => $options['data']['translator']->trans('Database for unit testing (optional)'),
                'attr' => [
                    'class' => 'input-lg',
                    'placeholder' => $options['data']['translator']->trans('tricolore_tests')
                ]
            ])
            ->add('database_username', 'text', [
                'constraints' => [
                    new Assert\NotBlank()
                ],
                'label' => $options['data']['translator']->trans('Database username'),
                'attr' => [
                    'class' => 'input-lg',
                    'placeholder' => $options['data']['translator']->trans('PostgreSQL username')
                ]
            ])
            ->add('database_password', 'password', [
                'constraints' => [
                    new Assert\NotBlank()
                ],
                'label' => $options['data']['translator']->trans('Database password'),
                'attr' => [
                    'class' => 'input-lg',
                    'placeholder' => $options['data']['translator']->trans('PostgreSQL password')
                ]
            ])
            ->add('database_prefix', 'text', [
                'constraints' => [
                    new Assert\NotBlank()
                ],
                'label' => $options['data']['translator']->trans('Prefix for tables (optional)'),
                'attr' => [
                    'class' => 'input-lg',
                    'placeholder' => $options['data']['translator']->trans('tricolore_')
                ]
            ])
            ->add($builder->create('administrator_account', 'form', [
                'label_attr' => [
                    'class' => 'installer-step-title'
                ], 'virtual' => true
            ]))
            ->add('admin_username', 'text', [
                'constraints' => [
                    new Assert\NotBlank()
                ],
                'label' => $options['data']['translator']->trans('Username'),
                'attr' => [
                    'class' => 'input-lg',
                    'placeholder' => $options['data']['translator']->trans('Administrator username')
                ]
            ])
            ->add('admin_password', 'password', [
                'constraints' => [
                    new Assert\NotBlank()
                ],
                'label' => $options['data']['translator']->trans('Password'),
                'attr' => [
                    'class' => 'input-lg',
                    'placeholder' => $options['data']['translator']->trans('Administrator password')
                ]
            ])
            ->add('admin_password_confirm', 'password', [
                'constraints' => [
                    new Assert\NotBlank()
                ],
                'label' => $options['data']['translator']->trans('Confirm password'),
                'attr' => [
                    'class' => 'input-lg',
                    'placeholder' => $options['data']['translator']->trans('Confirm password')
                ]
            ])
            ->add('admin_email', 'email', [
                'constraints' => [
                    new Assert\NotBlank()
                ],
                'label' => $options['data']['translator']->trans('E-Mail'),
                'attr' => [
                    'class' => 'input-lg',
                    'placeholder' => $options['data']['translator']->trans('Administrator e-mail')
                ]
            ])
            ->add('auth_submit', 'submit', [
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

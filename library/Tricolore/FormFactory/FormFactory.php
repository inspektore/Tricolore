<?php
namespace Tricolore\FormFactory;

use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\Csrf\CsrfExtension;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\DefaultCsrfProvider;

class FormFactory
{
    /**
     * Get form factory
     * 
     * @return Symfony\Component\Form\FormFactory
     */
    public function getFactory()
    {
        $csrf_secret_token = 'some_secret_token';

        $csrf_provider = new DefaultCsrfProvider($csrf_secret_token);

        return Forms::createFormFactoryBuilder()
        ->addExtension(new CsrfExtension($csrf_provider))
        ->getFormFactory();
    }
}

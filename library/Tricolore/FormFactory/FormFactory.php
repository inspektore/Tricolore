<?php
namespace Tricolore\FormFactory;

use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\Csrf\CsrfExtension;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\DefaultCsrfProvider;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;

class FormFactory
{
    /**
     * Get form factory
     * 
     * @return Symfony\Component\Form\FormFactory
     */
    public function getFactory()
    {
        return Forms::createFormFactoryBuilder()
        ->addExtension($this->getCsrfExtension())
        ->addExtension($this->getValidatorExtension())
        ->addExtension($this->getHttpFoundationExtension())
        ->getFormFactory();
    }

    /**
     * CSRF extension
     * 
     * @return Symfony\Component\Form\Extension\Csrf\CsrfExtension
     */
    private function getCsrfExtension()
    {
        $csrf_secret_token = 'some_secret_token';

        $csrf_provider = new DefaultCsrfProvider($csrf_secret_token);

        return new CsrfExtension($csrf_provider);
    }

    /**
     * Validator extension
     * 
     * @return Symfony\Component\Form\Extension\Validator\ValidatorExtension
     */
    private function getValidatorExtension()
    {
        $validator = Validation::createValidator();

        return new ValidatorExtension($validator);
    }

    /**
     * HTTP Foundation extension
     * 
     * @return Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension
     */
    private function getHttpFoundationExtension()
    {
        return new HttpFoundationExtension();
    }
}

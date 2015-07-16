<?php
namespace Tricolore\Form;

use Tricolore\Session\Session;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\Csrf\CsrfExtension;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;

class Form
{
    /**
     * Get form factory
     * 
     * @return Symfony\Component\Form\FormFactory
     */
    public function getFactory()
    {
        return Forms::createFormFactoryBuilder()
            ->addExtensions([
                $this->getCsrfExtension(),
                $this->getValidatorExtension(),
                $this->getHttpFoundationExtension()
            ])
            ->getFormFactory();
    }

    /**
     * CSRF extension
     * 
     * @return Symfony\Component\Form\Extension\Csrf\CsrfExtension
     */
    private function getCsrfExtension()
    {
        return new CsrfExtension(Session::getInstance()->csrfProvider());
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

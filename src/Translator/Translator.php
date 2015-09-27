<?php
namespace Tricolore\Translator;

use Tricolore\Foundation\Application;
use Tricolore\Config\Config;
use Tricolore\Exception\NotFoundResourceException;
use Symfony\Component\Translation\Translator as TranslatorService;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\Loader\XliffFileLoader;

class Translator
{
    /**
     * Get translator
     * 
     * @param string $resource
     * @return Symfony\Component\Translation\Translator
     */
    public function getTranslator($resource = null)
    {
        $locale = Config::getParameter('trans.locale');
        $in_prod = Application::getInstance()->getEnv() === 'prod';
        $cache_dir = ($in_prod === true) ? Application::createPath(
            Config::getParameter('directory.storage') . '/translations') : null;

        $translator = new TranslatorService($locale, new MessageSelector(), $cache_dir);
        $translator->addLoader('xliff', new XliffFileLoader());

        $this->addResource($translator, $resource, $locale);
        $this->addValidatorResource($translator, $locale);

        $translator->setFallbackLocales(['en_EN']);

        return $translator;
    }

    /**
     * Add resource
     * 
     * @param Symfony\Component\Translation\Translator $translator
     * @param string $resource
     * @param string $locale
     * @throws Tricolore\Exception\NotFoundResourceException
     * @return void
     */
    private function addResource(TranslatorService $translator, $resource, $locale)
    {
        if ($resource === null) {
            $translator->addResource('xliff', 
                Application::createPath(
                    sprintf('app/translations/%s/messages.xliff', $locale)), 
                $locale);           
        } else {
            if (file_exists($resource) === false) {
                throw new NotFoundResourceException(sprintf('Translator resource "%s" not found.', $resource));
            }

            $translator->addResource('xliff', $resource, $locale);
        }
    }

    /**
     * Add validator resource
     * 
     * @param Symfony\Component\Translation\Translator $translator
     * @param string $locale
     * @return void
     */
    private function addValidatorResource(TranslatorService $translator, $locale)
    {
        $translator->addResource('xliff', 
            Application::createPath(
                sprintf('app/translations/%s/validators.xliff', $locale)), 
            $locale);  
    }
}

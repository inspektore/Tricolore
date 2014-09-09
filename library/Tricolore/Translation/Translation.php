<?php
namespace Tricolore\Translation;

use Tricolore\Application;
use Tricolore\Config\Config;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\Loader\XliffFileLoader;

class Translation
{
    /**
     * Get translator
     * 
     * @param string $resource
     * @param string $locale
     * @return Symfony\Component\Translation\Translator
     */
    public function getTranslator($resource = null, $locale = null)
    {
        if($locale === null) {
            $locale = Config::key('trans.locale');
        }

        $translator = new Translator($locale, new MessageSelector());
        $translator->addLoader('xliff', new XliffFileLoader());
        if($resource === null) {
            $translator->addResource('xliff', 
                Application::getInstance()->createPath(
                    sprintf('library:Tricolore:Translation:Resources:%s:%s.xliff', $locale, Config::key('trans.domain'))), 
                $locale);            
        } else {
            $translator->addResource('xliff', $resource, $locale);
        }

        $translator->setFallbackLocale(['en_EN']);

        return $translator;
    }
}

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
     * @return Symfony\Component\Translation\Translator
     */
    public function getTranslator()
    {
        $translator = new Translator(Config::key('trans.locale'), new MessageSelector());
        $translator->addLoader('xliff', new XliffFileLoader());
        $translator->addResource('xliff', 
            Application::getInstance()->createPath(
                sprintf('library:Tricolore:Translation:Resources:%s:%s.xliff', Config::key('trans.locale'), Config::key('trans.domain'))), 
            Config::key('trans.locale'));
        $translator->setFallbackLocale(['en_EN']);

        return $translator;
    }
}

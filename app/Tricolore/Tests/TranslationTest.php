<?php
namespace Tricolore\Tests;

use Tricolore\Application;

class TranslationTest extends \PHPUnit_Framework_TestCase
{
    private function getServiceLocator()
    {
        return $this->getMockForAbstractClass('Tricolore\Services\ServiceLocator');
    }

    private function getTranslator()
    {
        return $this->getServiceLocator()->get('translator', [
            Application::createPath('app:Tricolore:Tests:Fixtures:Translation_enEN.xliff'),
        ]);
    }

    public function testGetTranslatorWithoutResources()
    {
        $this->getServiceLocator()->get('translator');
    }

    public function testStandardTrans()
    {
        $expected = 'Translated unit!';
        $actual = $this->getTranslator()->trans('Example unit');

        $this->assertEquals($expected, $actual);
    }

    public function testPlaceholderTrans()
    {
        $expected = 'Welcome Peter';
        $actual = $this->getTranslator()->trans('Hello %name%', ['%name%' => 'Peter']);

        $this->assertEquals($expected, $actual);
    }

    public function testPluralizationTrans()
    {
        $expected = 'Bob has 6 apples';
        $actual = $this->getTranslator()->transChoice('There is one apple|There are %count% apples', 6, ['%count%' => 6]);

        $this->assertEquals($expected, $actual);        
    }

    public function testTwigIntegration()
    {
        $service_view = $this->getServiceLocator()->get('view');

        $expected = 'Translated template';
        $actual = $service_view->display(null, 'TestTranslationTemplate', [], true);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException Tricolore\Exception\NotFoundResourceException
     */
    public function testExceptionResourceNotFound()
    {
        return $this->getServiceLocator()->get('translator', ['fake/path']);
    }
}

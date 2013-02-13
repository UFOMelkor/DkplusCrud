<?php
/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Util
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Util;

use PHPUnit_Framework_TestCase as TestCase;
use Zend\Form\FormInterface as Form;

/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Util
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class EventResultVerifierTest extends TestCase
{
    /**
     * @test
     * @group unit
     * @group unit/module
     * @dataProvider getFormAndNotFormValues
     */
    public function canVerifyForms($eventResult, $isForm)
    {
        $this->assertSame((boolean) $isForm, EventResultVerifier::isForm($eventResult));
    }

    public function getFormAndNotFormValues()
    {
        return array(
            array('foo', false),
            array(new \stdClass(), false),
            array($this->getMockForAbstractClass('Zend\Form\FormInterface'), true)
        );
    }

    /**
     * @test
     * @group unit
     * @group unit/module
     * @dataProvider getControllerResponsesAndNotControllerResponses
     */
    public function canVerifyControllerResponses($eventResult, $isControllerResponse)
    {
        $this->assertSame(
            (boolean) $isControllerResponse,
            EventResultVerifier::isControllerResponse($eventResult)
        );
    }

    public function getControllerResponsesAndNotControllerResponses()
    {
        return array(
            array('foo', false),
            array(new \stdClass(), false),
            array($this->getMockForAbstractClass('Zend\Form\FormInterface'), false),
            array($this->getMockForAbstractClass('Zend\Stdlib\ResponseInterface'), true),
            array($this->getMockForAbstractClass('Zend\View\Model\ModelInterface'), true),
        );
    }

    /**
     * @test
     * @group unit
     * @group unit/module
     * @dataProvider getNullAndNotNullValues
     */
    public function canVerifyNotNull($eventResult, $isNotNullResult)
    {
        $this->assertSame(
            (boolean) $isNotNullResult,
            EventResultVerifier::isNotNull($eventResult)
        );
    }

    public function getNullAndNotNullValues()
    {
        return array(
            array('foo', true),
            array(new \stdClass(), true),
            array(0, true),
            array('', true),
            array(-1, true),
            array(null, false),
        );
    }
}

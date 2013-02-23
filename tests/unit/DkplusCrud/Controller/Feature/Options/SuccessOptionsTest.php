<?php
/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Feature\Options;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class SuccessOptionsTest extends TestCase
{
    /** @test */
    public function isAnOptionsInstance()
    {
        $this->assertInstanceOf('Zend\Stdlib\AbstractOptions', new SuccessOptions());
    }

    /** @test */
    public function providesRedirectRoute()
    {
        $options = new SuccessOptions();
        $options->setRedirectRoute('foo/bar');

        $this->assertSame('foo/bar', $options->getRedirectRoute());
    }

    /** @test */
    public function providesHomeAsInitialRedirectRoute()
    {
        $options = new SuccessOptions();

        $this->assertSame('home', $options->getRedirectRoute());
    }

    /** @test */
    public function providesRedirectRouteParams()
    {
        $options = new SuccessOptions();
        $options->setRedirectRouteParams(array('foo' => 'bar'));

        $this->assertSame(array('foo' => 'bar'), $options->getComputatedRedirectRouteParams(null));
    }

    /** @test */
    public function providesAnEmptyArrayAsInitialRedirectRouteParams()
    {
        $options = new SuccessOptions();

        $this->assertSame(array(), $options->getComputatedRedirectRouteParams(null));
    }

    /** @test */
    public function canUseAnCallbackAsRedirectRouteParams()
    {
        $callbackObject = $this->getMock('stdClass', array('execute'));
        $callbackObject->expects($this->once())
                       ->method('execute')
                       ->will($this->returnValue(array('foo' => 'bar')));

        $options = new SuccessOptions();
        $options->setRedirectRouteParams(array($callbackObject, 'execute'));

        $this->assertSame(array('foo' => 'bar'), $options->getComputatedRedirectRouteParams(null));
    }

    /** @test */
    public function canUseTheGivenEntityWithinTheRedirectRouteParamsCallback()
    {
        $entity = $this->getMock('stdClass');

        $callbackObject = $this->getMock('stdClass', array('execute'));
        $callbackObject->expects($this->once())
                       ->method('execute')
                       ->with($entity);

        $options = new SuccessOptions();
        $options->setRedirectRouteParams(array($callbackObject, 'execute'));

        $options->getComputatedRedirectRouteParams($entity);
    }

    /** @test */
    public function providesMessage()
    {
        $options = new SuccessOptions();
        $options->setMessage('successful done');

        $this->assertSame('successful done', $options->getComputatedMessage(null));
    }

    /** @test */
    public function providesAnEmptyStringAsInitialMessage()
    {
        $options = new SuccessOptions();

        $this->assertSame('', $options->getComputatedMessage(null));
    }

    /** @test */
    public function canUseAnCallbackAsMessage()
    {
        $callbackObject = $this->getMock('stdClass', array('execute'));
        $callbackObject->expects($this->once())
                       ->method('execute')
                       ->will($this->returnValue(':-)'));

        $options = new SuccessOptions();
        $options->setMessage(array($callbackObject, 'execute'));

        $this->assertSame(':-)', $options->getComputatedMessage(null));
    }

    /** @test */
    public function canUseTheGivenEntityWithinTheMessageCallback()
    {
        $entity = $this->getMock('stdClass');

        $callbackObject = $this->getMock('stdClass', array('execute'));
        $callbackObject->expects($this->once())
                       ->method('execute')
                       ->with($entity);

        $options = new SuccessOptions();
        $options->setMessage(array($callbackObject, 'execute'));

        $options->getComputatedMessage($entity);
    }

    /** @test */
    public function providesAMessageNamespace()
    {
        $options = new SuccessOptions();
        $options->setMessageNamespace('alright');

        $this->assertEquals('alright', $options->getMessageNamespace());
    }

    /** @test */
    public function hasSuccessAsDefaultMessageNamespace()
    {
        $options = new SuccessOptions();

        $this->assertEquals('success', $options->getMessageNamespace());
    }
}

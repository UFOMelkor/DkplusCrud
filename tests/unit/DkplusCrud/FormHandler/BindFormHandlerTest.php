<?php
/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage FormHandler
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\FormHandler;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage FormHandler
 * @author     Oskar Bley <oskar@programming-php.net>
 * @covers     DkplusCrud\FormHandler\BindFormHandler
 */
class BindFormHandlerTest extends TestCase
{
    /** @var \Zend\Form\FormInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $form;

    /** @var BindFormHandler */
    private $formHandler;

    protected function setUp()
    {
        parent::setUp();

        $this->form        = $this->getMockForAbstractClass('Zend\Form\FormInterface');
        $this->formHandler = new BindFormHandler($this->form, 'stdClass');
    }

    /** @test */
    public function isAFormStrategy()
    {
        $this->assertInstanceOf('DkplusCrud\FormHandler\FormHandlerInterface', $this->formHandler);
    }

    /** @test */
    public function returnsTheOvergivenFormAsCreationForm()
    {
        $this->assertSame($this->form, $this->formHandler->getCreationForm());
    }

    /** @test */
    public function bindsAnInstanceOfTheModelToTheCreationForm()
    {
        $this->form->expects($this->once())
                   ->method('bind')
                   ->with($this->isInstanceOf('stdClass'));
        $this->formHandler->getCreationForm();
    }

    /** @test */
    public function returnsTheOvergivenFormAsUpdateForm()
    {
        $entity = $this->getMock('stdClass');
        $this->assertSame($this->form, $this->formHandler->getUpdateForm($entity));
    }

    /** @test */
    public function bindsTheGivenInstanceOfTheModelToTheUpdateForm()
    {
        $entity = $this->getMock('stdClass');

        $this->form->expects($this->once())
                   ->method('bind')
                   ->with($entity);
        $this->formHandler->getUpdateForm($entity);
    }

    /** @test */
    public function doesNotNeedToCreateNewEntitiesBecauseTheyAreAlreadyCreated()
    {
        $entity = $this->getMock('stdClass');
        $this->assertSame($entity, $this->formHandler->createEntity($entity));
    }

    /** @test */
    public function doesNotNeedToUpdateNewEntitiesBecauseTheyAreAlreadyUpdated()
    {
        $data = array('foo', 'bar', 'baz');
        $entity = $this->getMock('stdClass');
        $this->assertSame($entity, $this->formHandler->updateEntity($data, $entity));
    }
}

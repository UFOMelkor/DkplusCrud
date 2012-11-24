<?php
/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Controller\Action
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Action;

/**
 * @category   DkplusTest
 * @package    Crud
 * @subpackage Controller\Action
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class UpdateFormActionTest extends ActionTestCase
{
    protected function setUp()
    {
        $this->actionName = 'update';
        $this->action     = new UpdateFormAction($this->actionName);
        parent::setUp();
    }

    /**
     * @test
     * @group unit
     * @group unit/controller
     */
    public function isInitiallyNotStrict()
    {
        $this->assertFalse($this->action->isStrict());
    }
}

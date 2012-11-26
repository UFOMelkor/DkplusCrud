<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Feature;

use Zend\EventManager\EventInterface as Event;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class NotFoundReplacing extends AbstractFeature
{
    /** @var Options\NotFoundReplaceOptions */
    private $options;

    public function __construct(Options\NotFoundReplaceOptions $options)
    {
        $this->options = $options;
    }

    public function execute(Event $event)
    {
        $opt        = $this->options;
        $controller = $this->getController();

        $dsl = $controller->dsl()->replaceContent()->with()->controllerAction(
            $opt->getContentReplaceController(),
            $opt->getContentReplaceAction(),
            $opt->getContentReplaceRouteParams()
        )->and()->with()->route($opt->getContentReplaceRoute())
         ->and()->pageNotFound()->but()->ignore404NotFoundController();

        if ($opt->hasErrorMessage()) {
            $dsl->and()->add()->notFound()->message($opt->getErrorMessage());
        }
        return $dsl;
    }
}

<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Service\ServiceInterface as Service;
use Zend\EventManager\EventInterface;

/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */
class FormSubmission extends AbstractFeature
{
    /** @var Options\SuccessOptions */
    protected $options;

    /** @var Service */
    protected $service;

    /** @var string */
    protected $template;

    public function __construct(Service $service, Options\SuccessOptions $options, $template)
    {
        $this->service  = $service;
        $this->options  = $options;
        $this->template = $template;
    }

    public function execute(EventInterface $event)
    {
        $opt        = $this->options;
        $ctrl       = $this->getController();
        $form       = $event->getParam('form');
        $identifier = $event->getParam('identifier');
        $storage    = $identifier === null
                    ? array($this->service, 'create')
                    : array($this->service, 'update');

        return $ctrl->dsl()
                    ->render($this->template)
                    ->and()->use($form)->and()->assign()
                    ->and()->validate()->against('postredirectget')
                    ->and()->onSuccess(
                        $ctrl->dsl()
                             ->store()->formData()->into($storage)->with($identifier)
                             ->and()->redirect()
                             ->to()->route(
                                 $opt->getRedirectRoute(),
                                 array($opt, 'getComputatedRedirectRouteParams')
                             )
                             ->with()->success()->message(array($opt, 'getComputatedMessage'))
                    );
    }
}

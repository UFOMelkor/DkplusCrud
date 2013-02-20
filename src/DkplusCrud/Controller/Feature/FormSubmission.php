<?php
/**
 * @category   Dkplus
 * @package    Crud
 * @subpackage Controller\Feature
 * @author     Oskar Bley <oskar@programming-php.net>
 */

namespace DkplusCrud\Controller\Feature;

use Zend\EventManager\EventInterface;
use Zend\Stdlib\ResponseInterface as Response;

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

    /** @var callable */
    protected $storage;

    /** @var string */
    protected $template;

    /** @var string */
    protected $identifier = 'id';

    /**
     * @param callable $storage
     * @param Options\SuccessOptions $options
     * @param string $template
     */
    public function __construct($storage, Options\SuccessOptions $options, $template)
    {
        $this->storage  = $storage;
        $this->options  = $options;
        $this->template = $template;
    }

    public function execute(EventInterface $event)
    {
        $opt        = $this->options;
        $ctrl       = $this->getController();
        $form       = $event->getParam('form'); /* @var $form \Zend\Form\FormInterface */
        $identifier = $form->get($this->identifier)->getValue();

        return $ctrl->dsl()
                    ->render($this->template)
                    ->and()->use($form)->and()->assign()
                    ->and()->validate()->against('postredirectget')
                    ->and()->onSuccess(
                        $ctrl->dsl()
                             ->store()->formData()->into($this->storage)->with($identifier)
                             ->and()->redirect()
                             ->to()->route(
                                 $opt->getRedirectRoute(),
                                 array($opt, 'getComputatedRedirectRouteParams')
                             )
                             ->with()->success()->message(array($opt, 'getComputatedMessage'))
                    );

        $event->getViewModel()->setTemplate($this->template);
        $event->getViewModel()->setVariable('form', $event->getForm());

        if (!$this->isXmlHttpRequest()) {
            $result = $event->getController()->postRedirectGet();

            if ($result instanceof Response) {
                $event->useResponseAsResult();
                $event->setResponse($result);
                return;
            }

            $data = 'getdata'; /* todo */

            $form->setData($data);
        }
    }
}

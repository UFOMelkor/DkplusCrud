<?php
/**
 * @license MIT
 * @link    https://github.com/UFOMelkor/DkplusCrud canonical source repository
 */

namespace DkplusCrud\Controller\Feature;

use DkplusCrud\Controller\Event;
use DkplusCrud\Service\Service;
use DkplusCrud\Service\Feature\Filter as ServiceFilter;

/**
 * Modifies a <code>EntitiesProvider`</code> or <code>PaginationProvider</code> by modifying the query.
 *
 * Can use route, post or query parameters or variables.
 *
 * @author Oskar Bley <oskar@programming-php.net>
 * @since  0.1.0
 */
class MultipleInputFilter extends AbstractFeature
{
    /** @var string Get input values from route. */
    const SOURCE_ROUTE = 'route';

    /** @var string Get input values from query. */
    const SOURCE_QUERY = 'query';

    /** @var string Get input values from post. */
    const SOURCE_POST = 'post';

    /** @var string Get input values from <code>$propertyInputMap</code>. */
    const SOURCE_PROPERTY_INPUT_MAP = null;

    /** @var string */
    const COMPARATOR_LIKE = 'like';

    /** @var string */
    const COMPARATOR_CONTAINING = '%like%';

    /** @var string */
    const COMPARATOR_STARTING_WITH = 'like%';

    /** @var string */
    const COMPARATOR_ENDING_WITH = '%like';

    /** @var string */
    const COMPARATOR_EQUALS = 'equals';

    /** @var string */
    const COMPARATOR_GREATER_THAN = 'greaterThan';

    /** @var string */
    const COMPARATOR_GREATER_THAN_EQUALS = 'greaterThanEquals';

    /** @var string */
    const COMPARATOR_LESS_THAN = 'lessThan';

    /** @var string */
    const COMPARATOR_LESS_THAN_EQUALS = 'lessThanEquals';

    /** @var string */
    protected $eventType = self::EVENT_TYPE_PRE;

    /** @var int */
    protected $priority = 2;

    /** @var Service */
    protected $service;

    /** @var array */
    protected $propertyInputMap;

    /** @var string|null */
    protected $source;

    /** @var string */
    protected $comparator = 'like';

    /** @var ServiceFilter The filter that will be used by the service. */
    private $filter;

    /**
     * @param Service $service
     * @param array $propertyInputMap Contains the properties of the entities respectiveley the db
     *                                columns as key and the name of the input parameter as value.
     */
    public function __construct(Service $service, array $propertyInputMap)
    {
        $this->service          = $service;
        $this->propertyInputMap = $propertyInputMap;
    }

    /** @return ServiceFilter */
    public function getFilter()
    {
        if ($this->filter === null) {
            $this->setFilter(new ServiceFilter());
        }
        return $this->filter;
    }

    public function setFilter(ServiceFilter $filter)
    {
        $this->filter = $filter;
    }

    /**
     * The comparator defines how to compare the found input values against the database.
     *
     * Default is <code>MultipleInputFilter::COMPARATOR_LIKE</code>
     *
     * @param string $comparator
     *
     * @see MultipleInputFilter::COMPARATOR_LIKE
     * @see MultipleInputFilter::COMPARATOR_CONTAINING
     * @see MultipleInputFilter::COMPARATOR_STARTING_WITH
     * @see MultipleInputFilter::COMPARATOR_ENDING_WITH
     * @see MultipleInputFilter::COMPARATOR_EQUALS
     * @see MultipleInputFilter::COMPARATOR_GREATER_THAN
     * @see MultipleInputFilter::COMPARATOR_GREATER_THAN_EQUALS
     * @see MultipleInputFilter::COMPARATOR_LESS_THAN
     * @see MultipleInputFilter::COMPARATOR_LESS_THAN_EQUALS
     */
    public function setComparator($comparator)
    {
        $this->comparator = $comparator;
    }

    /**
     * Defines where to get the values from.
     *
     * The values can be gotton from post, query or route or you can define them
     * directly in <code>$propertyInputMap</code> which is the default behaviour.
     *
     * @param string $source
     *
     * @see MultipleInputFilter::SOURCE_POST
     * @see MultipleInputFilter::SOURCE_QUERY
     * @see MultipleInputFilter::SOURCE_ROUTE
     * @see MultipleInputFilter::SOURCE_PROPERTY_INPUT_MAP
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * Connecting multiple inputs with an <code>and</code> which is the default behaviour.
     *
     * @return void
     */
    public function refineResults()
    {
        $this->getFilter()->refineResults();
    }

    /**
     * Connecting multiple inputs with an <code>or</code>.
     *
     * @return void
     */
    public function enlargeResults()
    {
        $this->getFilter()->enlargeResults();
    }

    public function execute(Event $event)
    {
        foreach ($this->propertyInputMap as $property => $input) {
            $this->setUpFilter($property, $input, $event);
        }

        $this->service->addFeature($this->getFilter());
    }

    protected function setUpFilter($property, $input, Event $event)
    {
        switch ($this->comparator) {
            case self::FILTER_GREATER_THAN_EQUALS:
                $this->getFilter()->greaterThanEquals($property, $this->getInputValue($input, $event));
                break;
            case self::FILTER_GREATER_THAN:
                $this->getFilter()->greaterThan($property, $this->getInputValue($input, $event));
                break;
            case self::FILTER_LESS_THAN_EQUALS:
                $this->getFilter()->lessThanEquals($property, $this->getInputValue($input, $event));
                break;
            case self::FILTER_LESS_THAN:
                $this->getFilter()->lessThan($property, $this->getInputValue($input, $event));
                break;
            case self::FILTER_EQUALS:
                $this->getFilter()->equals($property, $this->getInputValue($input, $event));
                break;
            case self::COMPARATOR_LIKE:
                $this->getFilter()->like($property, $this->getInputValue($input, $event));
                break;
            case self::FILTER_ENDING_WITH:
                $this->getFilter()->like($property, '%' . $this->getInputValue($input, $event));
                break;
            case self::FILTER_STARTING_WITH:
                $this->getFilter()->like($property, $this->getInputValue($input, $event) . '%');
                break;
            default:
                $this->getFilter()->like($property, '%' . $this->getInputValue($input, $event) . '%');
                break;
        }
    }

    protected function getInputValue($input, Event $event)
    {
        if ($this->source == self::SOURCE_POST) {
            return $event->getController()->params()->fromPost($input);
        } elseif ($this->source == self::SOURCE_QUERY) {
            return $event->getController()->params()->fromQuery($input);
        } elseif ($this->source == self::SOURCE_ROUTE) {
            return $event->getController()->params()->fromRoute($input);
        }

        return $input;
    }
}

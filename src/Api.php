<?php

namespace Prest;

use Phalcon\Mvc\Micro;
use Prest\Api\Endpoint;
use Prest\Api\Collection;
use Phalcon\Di\Injectable;
use Prest\Constants\Services;
use Prest\Api\Resource as ApiResource;
use Phalcon\Mvc\Micro\CollectionInterface;

/**
 * Prest\Api
 *
 * @property \Prest\QueryParsers\PhqlQueryParser $phqlQueryParser
 * @property \Prest\Api $application
 * @property \Prest\Di\FactoryDefault $di
 *
 * @package Prest
 */
class Api extends Micro
{
    protected $matchedRouteParts = null;
    protected $collectionsByIds = [];
    protected $collectionsByName = [];
    protected $endpointsByIds = [];

    /**
     * @return Collection[]
     */
    public function getCollections()
    {
        return array_values($this->collectionsByIds);
    }

    /**l
     * @param $name
     *
     * @return Collection|null
     */
    public function getCollection($name)
    {
        return $this->collectionsByName[$name] ?? null;
    }

    /**
     * Mounts a resource.
     *
     * @param ApiResource $resource
     * @return static
     */
    public function resource(ApiResource $resource): Api
    {
        $this->mount($resource);

        return $this;
    }

    public function mount(CollectionInterface $collection)
    {
        if ($collection instanceof Collection) {
            $collectionName = $collection->getName();
            if (!is_null($collectionName)) {
                $this->collectionsByName[$collectionName] = $collection;
            }

            $this->collectionsByIds[$collection->getIdentifier()] = $collection;

            /** @var Endpoint $endpoint */
            foreach ($collection->getEndpoints() as $endpoint) {
                $fullIdentifier = $collection->getIdentifier() . ' ' . $endpoint->getIdentifier();
                $this->endpointsByIds[$fullIdentifier] = $endpoint;
            }
        }

        return parent::mount($collection);
    }

    /**
     * @param Collection $collection
     *
     * @return static
     */
    public function collection(Collection $collection)
    {
        $this->mount($collection);

        return $this;
    }

    /**
     * @return \Prest\Api\Collection|null  The matched collection
     */
    public function getMatchedCollection()
    {
        $collectionIdentifier = $this->getMatchedRouteNamePart('collection');

        if (!$collectionIdentifier) {
            return null;
        }

        return $this->collectionsByIds[$collectionIdentifier] ?? null;
    }

    /**
     * @param string $key
     */
    protected function getMatchedRouteNamePart($key)
    {
        if (is_null($this->matchedRouteParts)) {
            $routeName = $this->getRouter()->getMatchedRoute()->getName();

            if (!$routeName) {
                return null;
            }

            $this->matchedRouteParts = @unserialize($routeName);
        }

        if (is_array($this->matchedRouteParts) && array_key_exists($key, $this->matchedRouteParts)) {
            return $this->matchedRouteParts[$key];
        }

        return null;
    }

    /**
     * @return \Prest\Api\Endpoint|null  The matched endpoint
     */
    public function getMatchedEndpoint()
    {
        $collectionIdentifier = $this->getMatchedRouteNamePart('collection');
        $endpointIdentifier = $this->getMatchedRouteNamePart('endpoint');

        if (!$endpointIdentifier) {
            return null;
        }

        $fullIdentifier = $collectionIdentifier . ' ' . $endpointIdentifier;

        return $this->endpointsByIds[$fullIdentifier] ?? null;
    }

    /**
     * Attaches a middleware to the API.
     *
     * @param object|callable $middleware
     * @param int $priority
     * @return static
     */
    public function attach($middleware, int $priority = 100): Api
    {
        if (is_object($middleware) && $middleware instanceof Injectable) {
            $middleware->setDI($this->getDI());
        }

        /** @var \Phalcon\Events\Manager $eventsManager */
        if (!$eventsManager = $this->getEventsManager()) {
            $eventsManager = $this->getDI()->get(Services::EVENTS_MANAGER);
            $this->setEventsManager($eventsManager);
        }

        $eventsManager->attach('micro', $middleware, $priority);

        return $this;
    }
}

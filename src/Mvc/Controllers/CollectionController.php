<?php

namespace Prest\Mvc\Controllers;

use Prest\Api\Endpoint;
use Prest\Api\Collection;

/**
 * Prest\Mvc\Controllers\CollectionController
 *
 * @package Prest\Mvc\Controllers
 */
class CollectionController extends FractalController
{
    /** @var Collection */
    protected $collection;

    /** @var Endpoint */
    protected $endpoint;

    /**
     * @return Collection
     */
    public function getCollection(): Collection
    {
        if (!$this->collection) {
            $this->collection = $this->application->getMatchedCollection();
        }

        return $this->collection;
    }

    /**
     * @return Endpoint
     */
    public function getEndpoint(): Endpoint
    {
        if (!$this->endpoint) {
            $this->endpoint = $this->application->getMatchedEndpoint();
        }

        return $this->endpoint;
    }
}

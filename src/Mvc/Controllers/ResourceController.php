<?php

namespace Prest\Mvc\Controllers;

use Prest\Api\Resource;
use Prest\Transformers\ModelTransformer;
use Prest\Transformers\Transformer;

/**
 * Prest\Mvc\Controllers\ResourceController
 *
 * @package Prest\Mvc\Controllers
 */
class ResourceController extends CollectionController
{
    /**
     * Tries to get current resource.
     *
     * @return \Prest\Api\Resource|null
     */
    public function getResource()
    {
        $collection = $this->getCollection();

        if (is_object($collection) && $collection instanceof Resource) {
            return $collection;
        }

        return null;
    }

    protected function createResourceCollectionResponse($collection, array $meta = [])
    {
        return $this->createCollectionResponse(
            $collection,
            $this->getTransformer(),
            $this->getResource()->getCollectionKey(),
            $meta
        );
    }

    protected function createResourceResponse($item, array $meta = [])
    {
        return $this->createItemResponse(
            $item,
            $this->getTransformer(),
            $this->getItemKey(),
            $meta
        );
    }

    protected function createResourceOkResponse($item, array $meta = [])
    {
        return $this->createItemOkResponse(
            $item,
            $this->getTransformer(),
            $this->getItemKey(),
            $meta
        );
    }

    /**
     * Gets resource transformer.
     *
     * @return Transformer
     */
    protected function getTransformer(): Transformer
    {
        $resource = $this->getResource();

        if (!is_object($resource) || !$resource instanceof Resource) {
            return new Transformer();
        }

        if (!$transformerClass = $resource->getTransformer()) {
            return new Transformer();
        }

        $transformer = new $transformerClass();

        if ($transformer instanceof ModelTransformer) {
            $transformer->setModelClass($this->getResource()->getModel());
        }

        return $transformer;
    }

    /**
     * Tries to get resource item key.
     *
     * @return string
     */
    protected function getItemKey(): string
    {
        $resource = $this->getResource();
        $resourceKey = 'item';

        if ($resource instanceof Resource) {
            $resourceKey = $resource->getItemKey();
        }

        return $resourceKey;
    }
}

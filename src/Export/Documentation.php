<?php

namespace Prest\Export;

use Prest\Mvc\Plugin;
use Prest\Api\Resource;
use Prest\Api\Collection;
use Phalcon\Mvc\Router\Route;
use Prest\Transformers\ModelTransformer;
use Prest\Export\Documentation\Endpoint;
use Prest\Export\Documentation\Collection as DocCollection;

/**
 * Prest\Export\Documentation
 *
 * @package Prest\Export
 */
class Documentation extends Plugin
{
    public $name;
    public $basePath;
    protected $routes = [];
    protected $collections = [];

    public function __construct($name, $basePath)
    {
        $this->name = $name;
        $this->basePath = $basePath;
    }

    public function addManyRoutes(array $routes)
    {
        /** @var Route $route */
        foreach ($routes as $route) {
            $this->addRoute($route);
        }
    }

    public function addRoute(Route $route)
    {
        if (@unserialize($route->getName())) {
            return;
        }

        $this->routes[] = $route;
    }

    public function addManyCollections(array $collections)
    {
        /** @var Collection $collection */
        foreach ($collections as $collection) {
            $this->addCollection($collection);
        }
    }

    public function addCollection(Collection $apiCollection)
    {
        $aclRoles = $this->acl->getRoles();

        $collection = new DocCollection();
        $collection->setName($apiCollection->getName());
        $collection->setDescription($apiCollection->getDescription());
        $collection->setPath($apiCollection->getPrefix());

        // Set fields
        if ($apiCollection instanceof Resource) {
            if ($modelClass = $apiCollection->getModel()) {
                if ($transformerClass = $apiCollection->getTransformer()) {
                    /** @var ModelTransformer $transformer */
                    $transformer = new $transformerClass;

                    if ($transformer instanceof ModelTransformer) {
                        $transformer->setModelClass($modelClass);

                        $responseFields = $transformer->getResponseProperties();
                        $dataTypes = $transformer->getModelDataTypes();

                        $fields = [];

                        foreach ($responseFields as $field) {
                            $fields[$field] = array_key_exists($field,
                                $dataTypes) ? $dataTypes[$field] : ModelTransformer::TYPE_UNKNOWN;
                        }

                        $collection->setFields($fields);
                    }
                }
            }
        }

        // Add endpoints
        foreach ($apiCollection->getEndpoints() as $apiEndpoint) {
            $endpoint = new Endpoint();
            $endpoint->setName($apiEndpoint->getName());
            $endpoint->setDescription($apiEndpoint->getDescription());
            $endpoint->setHttpMethod($apiEndpoint->getHttpMethod());
            $endpoint->setPath($apiEndpoint->getPath());
            $endpoint->setExampleResponse($apiEndpoint->getExampleResponse());

            $allowedRoleNames = [];

            /** @var \Phalcon\Acl\Role $role */
            foreach ($aclRoles as $role) {
                if ($this->acl->isAllowed($role->getName(), $apiCollection->getIdentifier(),
                    $apiEndpoint->getIdentifier())
                ) {
                    $allowedRoleNames[] = $role->getName();
                }
            }

            $endpoint->setAllowedRoles($allowedRoleNames);

            $collection->addEndpoint($endpoint);
        }

        $this->collections[] = $collection;
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function getCollections()
    {
        return $this->collections;
    }
}

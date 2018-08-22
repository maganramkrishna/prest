<?php

namespace Prest\Middleware;

use Prest\Api;
use Phalcon\Mvc\Micro;
use Phalcon\Events\Event;
use League\Fractal\Manager;
use Prest\Mvc\Plugin;
use Prest\Constants\Services;
use Phalcon\Mvc\Micro\MiddlewareInterface;

/**
 * Prest\Middleware\FractalMiddleware
 *
 * @package Prest\Middleware
 */
class FractalMiddleware extends Plugin implements MiddlewareInterface
{
    /**
     * @var bool
     */
    private $parseIncludes;

    /**
     * FractalMiddleware constructor.
     *
     * @param bool $parseIncludes
     */
    public function __construct($parseIncludes = null)
    {
        $this->setParseIncludes($parseIncludes);
    }

    /**
     * Before anything happens.
     *
     * @param Event $event
     * @param Api $api
     * @return bool
     */
    public function beforeExecuteRoute(Event $event, Api $api)
    {
        /** @var Manager $fractal */
        $fractal = $this->di->get(Services::FRACTAL_MANAGER);

        if (!$this->parseIncludes) {
            return true;
        }

        $include = $this->request->getQuery('include');

        if (!is_null($include)) {
            $fractal->parseIncludes($include);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @param Micro $api
     * @return bool
     */
    public function call(Micro $api)
    {
        return true;
    }

    /**
     * Enables or disables parsing includes.
     *
     * @param $parseIncludes
     * @return void
     */
    protected function setParseIncludes($parseIncludes)
    {
        // Enable by default
        if ($parseIncludes === null) {
            $this->parseIncludes = true;
            return;
        }

        $this->parseIncludes = (bool)$parseIncludes;
    }
}

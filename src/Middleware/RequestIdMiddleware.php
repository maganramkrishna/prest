<?php

namespace Prest\Middleware;

use Prest\Api;
use Ramsey\Uuid\Uuid;
use Phalcon\Mvc\Micro;
use Phalcon\Events\Event;
use Prest\Mvc\Plugin;
use Phalcon\Mvc\Micro\MiddlewareInterface;

/**
 * Prest\Middleware\RequestIdMiddleware
 *
 * Add a unique `X-Request-Id` header to each request for logging and debugging purposes.
 *
 * @package Prest\Middleware
 */
class RequestIdMiddleware extends Plugin implements MiddlewareInterface
{
    /**
     * Sets the response X-Request-Id header.
     *
     * @param Event $event
     * @param Api $api
     * @return bool
     */
    public function beforeHandleRoute(Event $event, Api $api)
    {
        if (!$requestId = $this->request->getHeader('X-Request-Id')) {
            $uuid4 = Uuid::uuid4();
            $requestId = $uuid4->toString();
        }

        $this->response->setHeader('X-Request-Id', $requestId);

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
}

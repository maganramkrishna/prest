<?php

namespace Prest\Middleware;

use Prest\Api;
use Phalcon\Mvc\Micro;
use Phalcon\Events\Event;
use Prest\Mvc\Plugin;
use Prest\Constants\ErrorCodes;
use Phalcon\Mvc\Micro\MiddlewareInterface;
use Prest\Exception\Http\NotFoundException;

/**
 * Prest\Middleware\NotFoundMiddleware
 *
 * @package Prest\Middleware
 */
class NotFoundMiddleware extends Plugin implements MiddlewareInterface
{
    /**
     * Called before checks Not-Found handler.
     *
     * @param Event $event
     * @param Api $api
     * @return bool
     * @throws NotFoundException
     */
    public function beforeNotFound(Event $event, Api $api)
    {
        throw new NotFoundException(ErrorCodes::GENERAL_NOT_FOUND);
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

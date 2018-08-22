<?php

namespace Prest\Middleware;

use Prest\Api;
use Phalcon\Mvc\Micro;
use Phalcon\Events\Event;
use Prest\Mvc\Plugin;
use Phalcon\Mvc\Micro\MiddlewareInterface;

/**
 * Prest\Middleware\OptionsResponseMiddleware
 *
 * @package Prest\Middleware
 */
class OptionsResponseMiddleware extends Plugin implements MiddlewareInterface
{
    /**
     * Before handle routing.
     *
     * @param Event $event
     * @param Api $api
     * @return bool
     */
    public function beforeHandleRoute(Event $event, Api $api) : bool
    {
        // OPTIONS request, just send the headers and respond OK
        if ($this->request->isOptions()) {
            $this->response->setJsonContent([
                'result' => 'OK',
            ]);

            return false;
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
}

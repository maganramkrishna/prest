<?php

namespace Prest\Middleware;

use Prest\Api;
use Phalcon\Mvc\Micro;
use Prest\Mvc\Plugin;
use Phalcon\Events\Event;
use Prest\Exception\AuthException;
use Phalcon\Mvc\Micro\MiddlewareInterface;

/**
 * Prest\Middleware\AuthenticationMiddleware
 *
 * @package Prest\Middleware
 */
class AuthenticationMiddleware extends Plugin implements MiddlewareInterface
{
    /**
     * Before anything happens.
     *
     * @param Event $event
     * @param Api $api
     * @return bool
     * @throws AuthException
     */
    public function beforeExecuteRoute(Event $event, Api $api) : bool
    {
        $token = $this->request->getToken();

        if ($token !== null) {
            $this->authManager->authenticateToken($token);
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

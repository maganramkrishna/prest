<?php

namespace Prest\Middleware;

use Prest\Api;
use Phalcon\Mvc\Micro;
use Phalcon\Events\Event;
use Prest\Mvc\Plugin;
use Prest\Constants\ErrorCodes;
use Phalcon\Mvc\Micro\MiddlewareInterface;
use Prest\Exception\AuthorizationException;

/**
 * Prest\Middleware\AuthorizationMiddleware
 *
 * @package Prest\Middleware
 */
class AuthorizationMiddleware extends Plugin implements MiddlewareInterface
{
    /**
     * Before anything happens.
     *
     * @param Event $event
     * @param Api $api
     * @return bool
     * @throws AuthorizationException
     */
    public function beforeExecuteRoute(Event $event, Api $api)
    {
        $collection = $api->getMatchedCollection();
        $endpoint = $api->getMatchedEndpoint();

        if (!$collection || !$endpoint) {
            return true;
        }

        $allowed = $this->acl->isAllowed(
            $this->userService->getRole(),
            $collection->getIdentifier(),
            $endpoint->getIdentifier()
        );

        if (!$allowed) {
            throw new AuthorizationException(
                ErrorCodes::ACCESS_DENIED,
                'Operation is not allowed',
                ['id' => $this->userService->getIdentity()]
            );
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

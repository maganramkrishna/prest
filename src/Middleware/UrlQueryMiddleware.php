<?php

namespace Prest\Middleware;

use Prest\Api;
use Phalcon\Mvc\Micro;
use Phalcon\Events\Event;
use Prest\Mvc\Plugin;
use Prest\Constants\Services;
use Phalcon\Mvc\Micro\MiddlewareInterface;

/**
 * Prest\Middleware\UrlQueryMiddleware
 *
 * @package Prest\Middleware
 */
class UrlQueryMiddleware extends Plugin implements MiddlewareInterface
{
    /**
     * Before anything happens.
     *
     * @param Event $event
     * @param Api $api
     * @return bool
     */
    public function beforeExecuteRoute(Event $event, Api $api) : bool
    {
        $params = $this->getDI()->get(Services::REQUEST)->getQuery();
        $query = $this->getDI()->get(Services::URL_QUERY_PARSER)->createQuery($params);

        $this->getDI()->get(Services::QUERY)->merge($query);

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

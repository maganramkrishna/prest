<?php

namespace Prest\Middleware;

use Prest\Api;
use Phalcon\Mvc\Micro;
use Phalcon\Events\Event;
use Prest\Mvc\Plugin;
use Prest\Constants\HttpMethods;
use Phalcon\Mvc\Micro\MiddlewareInterface;

/**
 * Prest\Middleware\CorsMiddleware
 *
 * @package Prest\Middleware
 */
class CorsMiddleware extends Plugin implements MiddlewareInterface
{
    /**
     * The allowed origins.
     * @var string[]
     */
    private $allowedOrigins = ['*'];

    /**
     * The allowed methods.
     * @var array
     */
    private $allowedMethods = HttpMethods::ALL;

    /**
     * The allowed headers.
     * @var array
     */
    private $allowedHeaders = [
        'Origin',
        'Authorization',
        'Content-Type',
        'Content-Range',
        'Content-Disposition',
        'X-Requested-With',
    ];

    /**
     * Cors constructor.
     *
     * @param array|null $allowedOrigins Allowed origins
     * @param array|null $allowedMethods Allowed methods
     * @param array|null $allowedHeaders Allowed headers
     */
    public function __construct(
        array $allowedOrigins = null,
        array $allowedMethods = null,
        array $allowedHeaders = null
    ) {
        if ($allowedOrigins !== null) {
            $this->allowedOrigins = $allowedOrigins;
        }

        if ($allowedMethods !== null) {
            $this->allowedMethods = $allowedMethods;
        }

        if ($allowedHeaders !== null) {
            $this->allowedHeaders = $allowedHeaders;
        }
    }

    public function getAllowedOrigins() : array
    {
        return $this->allowedOrigins;
    }

    public function setAllowedOrigins(array $allowedOrigins = [])
    {
        $this->allowedOrigins = $allowedOrigins;
    }

    public function addAllowedOrigin($origin)
    {
        $this->allowedOrigins[] = $origin;
    }

    public function getAllowedMethods() : array
    {
        return $this->allowedMethods;
    }

    public function setAllowedMethods(array $allowedMethods = [])
    {
        $this->allowedMethods = $allowedMethods;
    }

    public function addAllowedMethod($method)
    {
        $this->allowedMethods[] = $method;
    }

    public function getAllowedHeaders() : array
    {
        return $this->allowedHeaders;
    }

    public function setAllowedHeaders(array $allowedHeaders = [])
    {
        $this->allowedHeaders = $allowedHeaders;
    }

    public function addAllowedHeader($header)
    {
        $this->allowedHeaders[] = $header;
    }

    /**
     * Before anything happens.
     *
     * @param Event $event
     * @param Api $api
     * @return bool
     */
    public function beforeHandleRoute(Event $event, Api $api) : bool
    {
        if (!count($this->allowedOrigins) || !$originValue = $this->getOriginValue()) {
            return true;
        }

        $this->response->setHeader('Access-Control-Allow-Origin', $originValue);
        $this->response->setHeader('Access-Control-Max-Age', '86400');

        // Note: wildcard requests are not supported when a request needs credentials.
        // So we set Access-Control-Allow-Credentials header
        $this->response->setHeader('Access-Control-Allow-Credentials', 'true');

        if (count($this->allowedMethods)) {
            $this->response->setHeader('Access-Control-Allow-Methods', implode(',', $this->allowedMethods));
        }

        if (count($this->allowedHeaders)) {
            $this->response->setHeader('Access-Control-Allow-Headers', implode(',', $this->allowedHeaders));
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
     * Gets ORIGIN value.
     *
     * @return string|null
     */
    protected function getOriginValue()
    {
        if (in_array('*', $this->allowedOrigins)) {
            return '*';
        }

        $origin = $this->request->getHeader('Origin');
        $originDomain = $origin ? parse_url($origin, PHP_URL_HOST) : null;

        if ($originDomain === null || $originDomain === false) {
            return null;
        }

        if ($this->isAllowedOrigin($originDomain)) {
            return $origin;
        }

        return null;
    }

    /**
     * Is the allowed passed domain?
     *
     * @param  string $originDomain
     * @return bool
     */
    protected function isAllowedOrigin(string $originDomain) : bool
    {
        if (empty($originDomain)) {
            return false;
        }

        foreach ($this->allowedOrigins as $allowedOrigin) {
            // First try exact domain
            if ($originDomain == $allowedOrigin) {
                return true;
            }

            // Parse wildcards
            $pattern = '/^' . str_replace('\*', '(.+)', preg_quote($allowedOrigin, '/')) . '$/i';
            if (preg_match($pattern, $originDomain) == 1) {
                return true;
            }
        }

        return false;
    }
}

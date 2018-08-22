<?php

namespace Prest\Di;

use Prest\Data\Query;
use Prest\Http\Request;
use Prest\Http\Response;
use League\Fractal\Manager;
use Prest\Constants\Services;
use Prest\Helpers\ErrorHelper;
use Prest\Helpers\FormatHelper;
use Prest\Acl\Adapter\Memory as Acl;
use Prest\Auth\Manager as AuthManager;
use Phalcon\Di\FactoryDefault as PhDi;
use Prest\User\Service as UserService;
use Prest\QueryParsers\PhqlQueryParser;
use Prest\Auth\TokenParsers\JWTTokenParser;
use Prest\Data\Query\QueryParsers\UrlQueryParser;

/**
 * Prest\Di\FactoryDefault
 *
 * @package Prest\Di
 */
class FactoryDefault extends PhDi
{
    /**
     * FactoryDefault constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setShared(Services::REQUEST, Request::class);
        $this->setShared(Services::RESPONSE, Response::class);
        $this->setShared(Services::AUTH_MANAGER, AuthManager::class);
        $this->setShared(Services::USER_SERVICE, UserService::class);

        $this->setShared(Services::TOKEN_PARSER, function () {
            // NOTE: You have to change secret
            return new JWTTokenParser('secret');
        });

        $this->setShared(Services::QUERY, Query::class);
        $this->setShared(Services::URL_QUERY_PARSER, UrlQueryParser::class);
        $this->setShared(Services::ACL, Acl::class);
        $this->setShared(Services::ERROR_HELPER, ErrorHelper::class);
        $this->setShared(Services::FORMAT_HELPER, FormatHelper::class);

        $this->setShared(Services::FRACTAL_MANAGER, Manager::class);
        $this->setShared(Services::PHQL_QUERY_PARSER, PhqlQueryParser::class);
    }
}

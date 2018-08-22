<?php

namespace Prest\Mvc;

use Phalcon\Mvc\User\Plugin as PhPlugin;

/**
 * Prest\Mvc\Plugin
 *
 * @property \Prest\Api $application
 * @property \Prest\Di\FactoryDefault $di
 * @property \Prest\Http\Request $request
 * @property \Prest\Http\Response $response
 * @property \Phalcon\Acl\AdapterInterface $acl
 * @property \Prest\Auth\Manager $authManager
 * @property \Prest\User\Service $userService
 * @property \Prest\Helpers\ErrorHelper $errorHelper
 * @property \Prest\Helpers\FormatHelper $formatHelper
 * @property \Prest\Auth\TokenParserInterface $tokenParser
 * @property \Prest\Data\Query $query
 * @property \Prest\Data\Query\QueryParsers\UrlQueryParser $urlQueryParser
 * @property \Prest\QueryParsers\PhqlQueryParser $phqlQueryParser
 *
 * @package Prest\Mvc
 */
class Plugin extends PhPlugin
{
}

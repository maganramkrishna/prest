<?php

namespace Prest\Mvc;

use Phalcon\Mvc\Controller as PhController;

/**
 * Prest\Mvc\Controller
 *
 * @property \Prest\Api $application
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
 * @property \Prest\Di\FactoryDefault $di
 */
class Controller extends PhController
{
}

<?php

namespace Prest\Di;

use Phalcon\Di;
use Phalcon\DiInterface;
use Prest\Constants\ErrorCodes;
use Prest\Exception\RuntimeException;

/**
 * Prest\Di\InjectableTrait
 *
 * Dependency Injection Trait.
 * It should be used for classes which do not extend Injectable
 * and do not implement DiInterface interface.
 *
 * <code>
 * class A {
 *     // Some logic
 * }
 *
 * use Prest\Di\InjectableTrait;
 *
 * class B extends A {
 *     use InjectableTrait {
 *         InjectableTrait::__construct as protected injectDi;
 *     }
 *
 *     public function __construct()
 *     {
 *         $this->injectDi();
 *     }
 * }
 * </code>
 *
 * @property \Prest\QueryParsers\PhqlQueryParser $phqlQueryParser
 * @property \Prest\Api $application
 * @property \Prest\Di\FactoryDefault $di
 * @property \Prest\Http\Request $request
 * @property \Prest\Http\Response $response
 * @property \Prest\Auth\Manager $authManager
 * @property \Prest\User\Service $userService
 * @property \Prest\Helpers\ErrorHelper $errorHelper
 * @property \Prest\Helpers\FormatHelper $formatHelper
 * @property \Prest\Auth\TokenParserInterface $tokenParser
 * @property \Prest\Data\Query $query
 * @property \Prest\Data\Query\QueryParsers\UrlQueryParser $urlQueryParser
 * @property \Phalcon\Acl\AdapterInterface $acl
 * @property \Phalcon\Mvc\Dispatcher|\Phalcon\Mvc\DispatcherInterface $dispatcher
 * @property \Phalcon\Mvc\Router|\Phalcon\Mvc\RouterInterface $router
 * @property \Phalcon\Mvc\Url|\Phalcon\Mvc\UrlInterface $url
 * @property \Phalcon\Http\Response\Cookies|\Phalcon\Http\Response\CookiesInterface $cookies
 * @property \Phalcon\Filter|\Phalcon\FilterInterface $filter
 * @property \Phalcon\Flash\Direct $flash
 * @property \Phalcon\Flash\Session $flashSession
 * @property \Phalcon\Session\Adapter\Files|\Phalcon\Session\Adapter|\Phalcon\Session\AdapterInterface $session
 * @property \Phalcon\Events\Manager $eventsManager
 * @property \Phalcon\Db\AdapterInterface $db
 * @property \Phalcon\Security $security
 * @property \Phalcon\Crypt $crypt
 * @property \Phalcon\Tag $tag
 * @property \Phalcon\Escaper|\Phalcon\EscaperInterface $escaper
 * @property \Phalcon\Annotations\Adapter\Memory|\Phalcon\Annotations\Adapter $annotations
 * @property \Phalcon\Mvc\Model\Manager|\Phalcon\Mvc\Model\ManagerInterface $modelsManager
 * @property \Phalcon\Cache\BackendInterface $modelsCache
 * @property \Phalcon\Mvc\Model\MetaData\Memory|\Phalcon\Mvc\Model\MetadataInterface $modelsMetadata
 * @property \Phalcon\Mvc\Model\Transaction\Manager $transactionManager
 * @property \Phalcon\Assets\Manager $assets
 * @property \Phalcon\DI|\Phalcon\DiInterface $container
 * @property \Phalcon\Session\Bag $persistent
 * @property \Phalcon\Mvc\View|\Phalcon\Mvc\ViewInterface $view
 *
 * @package Prest\Di
 */
trait InjectableTrait
{
    /**
     * The Dependency Injection Container.
     * @var DiInterface
     */
    protected $dependencyInjector;

    /**
     * InjectableTrait constructor.
     *
     * @param DiInterface $dependencyInjector The Dependency Injection dependencyInjector [Optional].
     */
    public function __construct(DiInterface $dependencyInjector = null)
    {
        $dependencyInjector = $dependencyInjector ?: Di::getDefault();

        $this->setDI($dependencyInjector);
    }

    /**
     * Gets the Dependency Injection Container.
     *
     * @return DiInterface
     */
    public function getDI(): DiInterface
    {
        if (!$this->dependencyInjector instanceof DiInterface) {
            $this->dependencyInjector = FactoryDefault::getDefault();
        }

        return $this->dependencyInjector;
    }

    /**
     * Sets the Dependency Injection Container.
     *
     * @param DiInterface $dependencyInjector
     * @return $this
     */
    public function setDI(DiInterface $dependencyInjector)
    {
        $this->dependencyInjector = $dependencyInjector;

        return $this;
    }

    /**
     * Trying to obtain the dependence from the Dependency Injection Container.
     *
     * @param string $func
     * @param mixed $argv
     *
     * @return mixed
     */
    public function __call($func, $argv)
    {
        return call_user_func_array([$this->getDI(), $func], $argv);
    }

    /**
     * Magic method __get
     *
     * @param string $propertyName
     * @return mixed|DiInterface
     * @throws RuntimeException
     */
    public function __get(string $propertyName)
    {
        $dependencyInjector = $this->getDI();

        if ($dependencyInjector->has($propertyName)) {
            $service = $dependencyInjector->getShared($propertyName);
            $this->{$propertyName} = $service;

            return $service;
        }

        if ($propertyName == "di") {
            $this->{"di"} = $dependencyInjector;
            return $dependencyInjector;
        }

        throw new RuntimeException(
            ErrorCodes::GENERAL_SYSTEM,
            "Access to undefined property {$propertyName}"
        );
    }
}

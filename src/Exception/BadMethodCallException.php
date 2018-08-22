<?php

namespace Prest\Exception;

/**
 * Prest\Exception\BadMethodCallException
 *
 * @package Prest\Exception
 */
class BadMethodCallException extends \BadMethodCallException implements ExceptionInterface
{
    use ExceptionInfoAwareTrait;
}

<?php

namespace Prest\Exception;

/**
 * Prest\Exception\NotImplementedException
 *
 * @package Prest\Exception
 */
class NotImplementedException extends \BadMethodCallException implements ExceptionInterface
{
    use ExceptionInfoAwareTrait;
}

<?php

namespace Prest\Exception;

/**
 * Prest\Exception\LogicException
 *
 * @package Prest\Exception
 */
class LogicException extends \DomainException implements ExceptionInterface
{
    use ExceptionInfoAwareTrait;
}

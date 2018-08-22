<?php

namespace Prest\Exception;

/**
 * Prest\Exception\AuthException
 *
 * @package Prest\Exception
 */
class AuthException extends \DomainException implements ExceptionInterface
{
    use ExceptionInfoAwareTrait;
}

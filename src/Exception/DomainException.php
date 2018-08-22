<?php

namespace Prest\Exception;

/**
 * Prest\Exception\DomainException
 *
 * @package Prest\Exception
 */
class DomainException extends \DomainException implements ExceptionInterface
{
    use ExceptionInfoAwareTrait;
}

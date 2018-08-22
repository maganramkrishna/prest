<?php

namespace Prest\Exception;

/**
 * Prest\Exception\InvalidArgumentException
 *
 * @package Prest\Exception
 */
class InvalidArgumentException extends \InvalidArgumentException implements ExceptionInterface
{
    use ExceptionInfoAwareTrait;
}

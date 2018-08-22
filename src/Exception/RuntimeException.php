<?php

namespace Prest\Exception;

/**
 * Prest\Exception\RuntimeException
 *
 * @package Prest\Exception
 */
class RuntimeException extends \RuntimeException implements ExceptionInterface
{
    use ExceptionInfoAwareTrait;
}

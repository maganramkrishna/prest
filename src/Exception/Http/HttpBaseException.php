<?php

namespace Prest\Exception\Http;

use Prest\Exception\ExceptionInterface;
use Prest\Exception\ExceptionInfoAwareTrait;

/**
 * Prest\Exception\Http\HttpBaseException
 *
 * @package Prest\Exception
 */
class HttpBaseException extends \RuntimeException implements ExceptionInterface
{
    use ExceptionInfoAwareTrait;
}

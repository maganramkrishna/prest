<?php

namespace Prest\Http;

use Phalcon\Http\Response as PhResponse;
use Prest\Http\Concerns\ExceptionAwareResponse;

/**
 * Prest\Http\Response
 *
 * @package Prest\Http
 */
class Response extends PhResponse
{
    use ExceptionAwareResponse;

    public function setErrorContent(\Exception $exception, $useDeveloperInfo = false, $addTraceToOutput = false)
    {
        $response = array_merge(
            $this->composeErrorMessage($exception),
            $this->composeErrorResponse($exception, $useDeveloperInfo, $addTraceToOutput)
        );

        $this->setStatusCode($response['statusCode']);

        unset($response['statusCode']);
        $this->setJsonContent(['error' => $response]);
    }

    public function setJsonContent($content, $jsonOptions = 0, $depth = 512)
    {
        parent::setJsonContent($content, $jsonOptions, $depth);

        $this->setContentType('application/json', 'UTF-8');
        $this->setHeader('E-Tag', md5($this->getContent()));
    }
}

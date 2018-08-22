<?php

namespace Prest\Http\Concerns;

use Phalcon\DiInterface;
use Prest\Constants\Services;
use Prest\Helpers\ErrorHelper;
use Prest\Exception\ExceptionInterface;

/**
 * Prest\Http\Concerns\ExceptionAwareResponse
 *
 * @method DiInterface getDI()
 *
 * @package Prest\Http\Concerns
 */
trait ExceptionAwareResponse
{
    /**
     * Prepare public part of the response.
     *
     * @param \Exception $exception
     * @return array
     */
    protected function composeErrorMessage(\Exception $exception)
    {
        $statusCode = 500;
        $errorCode  = $exception->getCode();
        $message    = $exception->getMessage();

        $response = compact('statusCode', 'errorCode', 'message');

        if ($this->getErrorHelper()->has($errorCode)) {
            $defaultMessage = $this->getErrorHelper()->get($errorCode);

            $response['statusCode'] = $defaultMessage['statusCode'];

            if (empty($response['message'])) {
                $response['message'] = $defaultMessage['message'];
            }
        }

        return $response;
    }

    /**
     * Prepare error part of the response.
     *
     * @param \Exception $exception
     * @param bool $useDeveloperInfo
     * @param bool $addTraceToOutput
     * @return array
     */
    protected function composeErrorResponse(
        \Exception $exception,
        $useDeveloperInfo = false,
        $addTraceToOutput = false
    ): array {
        $response = [];

        if ($exception instanceof ExceptionInterface) {
            $response['info'] = $exception->getUserInfo();
        }

        if ($useDeveloperInfo) {
            /** @var \Prest\Http\Request $request */
            $request = $this->getDI()->get(Services::REQUEST);

            $developerResponse = [
                'file'    => $exception->getFile(),
                'line'    => $exception->getLine(),
                'request' => "{$request->getMethod()} {$request->getURI()}",
            ];

            if ($exception instanceof ExceptionInterface && $exception->getDeveloperInfo() != null) {
                $developerResponse['info'] = $exception->getDeveloperInfo();
            }

            $response['developer'] = $developerResponse;
        }

        if ($addTraceToOutput) {
            $response['info'] = $exception->getTrace();
        }

        return $response;
    }

    /**
     * Gets error helper.
     *
     * @return ErrorHelper
     */
    protected function getErrorHelper(): ErrorHelper
    {
        if (!$this->getDI()->has(Services::ERROR_HELPER)) {
            $this->getDI()->setShared(Services::ERROR_HELPER, ErrorHelper::class);
        }

        return $this->getDI()->get(Services::ERROR_HELPER);
    }
}

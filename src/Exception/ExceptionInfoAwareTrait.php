<?php

namespace Prest\Exception;

use Throwable;

/**
 * Prest\Exception\ExceptionInfoAwareTrait
 *
 * @package Prest\Exception
 */
trait ExceptionInfoAwareTrait
{
    /**
     * Developer info.
     * @var mixed
     */
    protected $developerInfo;

    /**
     * Developer info.
     * @var mixed
     */
    protected $userInfo;

    /**
     * ExceptionInfoAwareTrait constructor.
     *
     * Construct the exception. Note: The message is NOT binary safe.
     *
     * @param int $code The Exception code.
     * @param string $message [optional] The Exception message to throw.
     * @param mixed [optional] $developerInfo Developer info.
     * @param mixed [optional] $userInfo User info.
     * @param Throwable $previous [optional] The previous throwable used for the exception chaining.
     */
    public function __construct(
        int $code,
        string $message = null,
        $developerInfo = null,
        $userInfo = null,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);

        $this->developerInfo = $developerInfo;
        $this->userInfo = $userInfo;
    }

    /**
     * {@inheritdoc}
     *
     * @return mixed
     */
    public function getDeveloperInfo()
    {
        return $this->developerInfo;
    }

    /**
     * {@inheritdoc}
     *
     * @return mixed
     */
    public function getUserInfo()
    {
        return $this->userInfo;
    }
}

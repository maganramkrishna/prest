<?php

namespace Prest\Exception;

/**
 * Prest\Exception\ExceptionInterface
 *
 * @package Prest\Exception
 */
interface ExceptionInterface
{
    /**
     * Gets developer info.
     *
     * @return mixed
     */
    public function getDeveloperInfo();

    /**
     * Gets user info.
     *
     * @return mixed
     */
    public function getUserInfo();
}

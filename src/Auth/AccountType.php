<?php

namespace Prest\Auth;

/**
 * Prest\Auth\AccountType
 *
 * @package Prest\Auth
 */
interface AccountType
{
    /**
     * Gets entity identity.
     *
     * @param array $data Login data
     * @return string|null
     */
    public function login($data);

    /**
     * Authenticates with use of provided identity.
     *
     * @param string $identity Identity
     * @return bool
     */
    public function authenticate($identity);
}

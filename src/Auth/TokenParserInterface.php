<?php

namespace Prest\Auth;

/**
 * Prest\Auth\TokenParserInterface
 *
 * @package Prest\Auth
 */
interface TokenParserInterface
{
    /**
     * Gets generated token.
     *
     * @param Session $session Session to generate token for
     * @return string
     */
    public function getToken(Session $session): string;

    /**
     * Gets session restored from token.
     *
     * @param string $token Access token
     * @return Session
     */
    public function getSession($token): Session;
}

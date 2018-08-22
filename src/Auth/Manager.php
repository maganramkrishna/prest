<?php

namespace Prest\Auth;

use Prest\Mvc\Plugin;
use Prest\Constants\ErrorCodes;
use Prest\Exception\AuthException;

/**
 * Prest\Auth\Manager
 *
 * @package Prest\Auth
 */
class Manager extends Plugin
{
    const LOGIN_DATA_USERNAME = 'username';
    const LOGIN_DATA_PASSWORD = 'password';

    /**
     * Account types
     * @var AccountType[]
     */
    protected $accountTypes;

    /**
     * Currently active session
     * @var Session|null
     */
    protected $session;

    /**
     * Expiration time of created sessions
     * @var int
     */
    protected $sessionDuration;

    /**
     * Manager constructor.
     *
     * @param int $sessionDuration
     */
    public function __construct(int $sessionDuration = 86400)
    {
        $this->sessionDuration = $sessionDuration;

        $this->accountTypes = [];
        $this->session = null;
    }


    public function registerAccountType($name, AccountType $account)
    {
        $this->accountTypes[$name] = $account;

        return $this;
    }

    public function getAccountTypes()
    {
        return $this->accountTypes;
    }

    public function getSessionDuration()
    {
        return $this->sessionDuration;
    }

    public function setSessionDuration($time)
    {
        $this->sessionDuration = $time;
    }


    public function getSession()
    {
        return $this->session;
    }

    public function setSession(Session $session)
    {
        $this->session = $session;
    }

    /**
     * Checks if a user is currently logged in.
     *
     * @return bool
     */
    public function loggedIn(): bool
    {
        return !!$this->session;
    }

    /**
     * Helper to login with username & password.
     *
     * @param string $accountTypeName
     * @param string $username
     * @param string $password
     *
     * @return Session Created session
     * @throws AuthException
     */
    public function loginWithUsernamePassword($accountTypeName, $username, $password)
    {
        return $this->login($accountTypeName, [
            static::LOGIN_DATA_USERNAME => $username,
            static::LOGIN_DATA_PASSWORD => $password,
        ]);
    }

    /**
     * Login a user with the specified account-type.
     *
     * @param string $accountTypeName
     * @param array $data
     *
     * @return Session Created session
     * @throws AuthException
     */
    public function login($accountTypeName, array $data)
    {
        $account = $this->getAccountType($accountTypeName);

        $identity = $account->login($data);

        if (empty($identity)) {
            throw new AuthException(ErrorCodes::AUTH_LOGIN_FAILED);
        }

        $startTime = time();

        $session = new Session($accountTypeName, $identity, $startTime, $startTime + $this->sessionDuration);
        $token = $this->tokenParser->getToken($session);
        $session->setToken($token);

        $this->session = $session;

        return $this->session;
    }

    /**
     * Get AccountType instance.
     *
     * @param string $name
     * @return AccountType
     * @throws AuthException
     */
    public function getAccountType($name): AccountType
    {
        if (!isset($this->accountTypes[$name])) {
            throw new AuthException(ErrorCodes::AUTH_INVALID_ACCOUNT_TYPE);
        }

        return $this->accountTypes[$name];
    }

    /**
     * Authenticate any further requests using the received token from the initial authentication.
     *
     * @param string $token Token to authenticate with
     *
     * @return bool
     * @throws AuthException
     */
    public function authenticateToken($token): bool
    {
        try {
            $session = $this->tokenParser->getSession($token);
        } catch (\Exception $e) {
            throw new AuthException(ErrorCodes::AUTH_TOKEN_INVALID);
        }

        if (!$session) {
            return false;
        }

        if ($session->getExpirationTime() < time()) {
            throw new AuthException(ErrorCodes::AUTH_SESSION_EXPIRED);
        }

        $session->setToken($token);

        // Authenticate identity
        $account = $this->getAccountType($session->getAccountTypeName());

        if (!$account->authenticate($session->getIdentity())) {
            throw new AuthException(ErrorCodes::AUTH_SESSION_INVALID);
        }

        $this->session = $session;

        return true;
    }
}

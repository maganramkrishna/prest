<?php

namespace Prest\User;

use Prest\Mvc\Plugin;
use Prest\Constants\ErrorCodes;
use Prest\Exception\NotImplementedException;

/**
 * Prest\User\Service
 *
 * Provides initial service to work with User model.
 * Usually this service have to be extend to amend with domain specific logic.
 *
 * @package Prest\User
 */
class Service extends Plugin
{
    /**
     * Returns details for the current user, e.g. a User model
     *
     * @return mixed
     */
    public function getDetails()
    {
        $details = null;

        $session = $this->authManager->getSession();

        if ($session) {
            $identity = $session->getIdentity();
            $details = $this->getDetailsForIdentity($identity);
        }

        return $details;
    }

    /**
     * This method should return details for the provided identity. Override this method with your own implementation.
     *
     * @param mixed $identity Identity to get the details from
     *
     * @return mixed The details for the provided identity
     * @throws NotImplementedException
     */
    protected function getDetailsForIdentity($identity)
    {
        $message = 'Unable to get details for identity, method getDetailsForIdentity in user service not implemented.' .
            ' Make a subclass of \Prest\User\Service with an implementation for this method,' .
            ' and register it in your DI.';

        throw new NotImplementedException(ErrorCodes::GENERAL_NOT_IMPLEMENTED, null, $message);
    }

    /**
     * Returns the identity for the current user, e.g. the user ID
     *
     * @return mixed
     */
    public function getIdentity()
    {
        $session = $this->authManager->getSession();

        if ($session) {
            return $session->getIdentity();
        }

        return null;
    }

    /**
     * This method should return the role for the current user
     *
     * @return string Name of the role for the current user
     * @throws NotImplementedException
     */
    public function getRole()
    {
        $message = 'Unable to get role for identity, method getRole in user service not implemented.' .
            ' Make a subclass of \Prest\User\Service with an implementation for this method,' .
            ' and register it in your DI.';

        throw new NotImplementedException(ErrorCodes::GENERAL_NOT_IMPLEMENTED, null, $message);
    }
}

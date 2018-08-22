<?php

namespace Prest\Auth\TokenParsers;

use Firebase\JWT\JWT;
use Prest\Auth\Session;
use Prest\Constants\ErrorCodes;
use Prest\Auth\TokenParserInterface;
use Prest\Exception\IllegalStateException;
use Prest\Exception\InvalidArgumentException;

/**
 * Prest\Auth\TokenParsers\JWTTokenParser
 *
 * @package Prest\Auth\TokenParsers
 */
class JWTTokenParser implements TokenParserInterface
{
    const ALGORITHM_HS256 = 'HS256';
    const ALGORITHM_HS512 = 'HS512';
    const ALGORITHM_HS384 = 'HS384';
    const ALGORITHM_RS256 = 'RS256';

    /**
     * @var string
     */
    protected $algorithm;

    /**
     * @var array|string
     */
    protected $secret;

    /**
     * JWTTokenParser constructor.
     *
     * @param array|string $secret
     * @param string $algorithm
     * @throws IllegalStateException
     */
    public function __construct($secret, $algorithm = self::ALGORITHM_HS256)
    {
        if (!class_exists(JWT::class)) {
            throw new IllegalStateException(
                ErrorCodes::GENERAL_SYSTEM,
                sprintf('The %s class is needed for the JWT token parser', JWT::class)
            );
        }

        $this->algorithm = $algorithm;
        $this->secret = $secret;
    }

    public function setAlgorithm($algorithm)
    {
        $this->algorithm = $algorithm;
    }

    public function setSecret($secret)
    {
        $this->secret = $secret;
    }

    /**
     * {@inheritdoc}
     *
     * @param Session $session Session to generate token for
     * @return string
     */
    public function getToken(Session $session): string
    {
        $tokenData = $this->create(
            $session->getAccountTypeName(),
            $session->getIdentity(),
            $session->getStartTime(),
            $session->getExpirationTime()
        );

        return $this->encode($tokenData);
    }

    protected function create($issuer, $user, $iat, $exp)
    {

        return [

            /*
            The iss (issuer) claim identifies the principal
            that issued the JWT. The processing of this claim
            is generally application specific.
            The iss value is a case-sensitive string containing
            a StringOrURI value. Use of this claim is OPTIONAL.
            ------------------------------------------------*/
            "iss" => $issuer,

            /*
            The sub (subject) claim identifies the principal
            that is the subject of the JWT. The Claims in a
            JWT are normally statements about the subject.
            The subject value MUST either be scoped to be
            locally unique in the context of the issuer or
            be globally unique. The processing of this claim
            is generally application specific. The sub value
            is a case-sensitive string containing a
            StringOrURI value. Use of this claim is OPTIONAL.
            ------------------------------------------------*/
            "sub" => $user,

            /*
            The iat (issued at) claim identifies the time at
            which the JWT was issued. This claim can be used
            to determine the age of the JWT. Its value MUST
            be a number containing a NumericDate value.
            Use of this claim is OPTIONAL.
            ------------------------------------------------*/
            "iat" => $iat,

            /*
            The exp (expiration time) claim identifies the
            expiration time on or after which the JWT MUST NOT
            be accepted for processing. The processing of the
            exp claim requires that the current date/time MUST
            be before the expiration date/time listed in the
            exp claim. Implementers MAY provide for some small
            leeway, usually no more than a few minutes,
            to account for clock skew. Its value MUST be a
            number containing a NumericDate value.
            Use of this claim is OPTIONAL.
            ------------------------------------------------*/
            "exp" => $exp,
        ];
    }

    /**
     * Converts and signs a PHP object or array into a JWT string.
     *
     * @param object|array $token PHP object or array
     * @return string
     * @throws InvalidArgumentException
     */
    public function encode($token): string
    {
        if (!is_string($this->secret)) {
            throw new InvalidArgumentException(
                ErrorCodes::GENERAL_SYSTEM,
                sprintf('To use %s:encode the %s:secret bust be a string', JWT::class, self::class)
            );
        }

        return JWT::encode($token, $this->secret, $this->algorithm);
    }

    /**
     * {@inheritdoc}
     *
     * @param string $token Access token
     * @return Session
     */
    public function getSession($token): Session
    {
        $tokenData = $this->decode($token);

        return new Session($tokenData->iss, $tokenData->sub, $tokenData->iat, $tokenData->exp, $token);
    }

    public function decode($token)
    {
        return JWT::decode($token, $this->secret, [$this->algorithm]);
    }
}

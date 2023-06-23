<?php

namespace Phapi\Utility;

use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Phalcon\DI;
use Phapi\Exceptions\UnauthorizedException;

class Auth
{
    // Currently implementing Sliding expiration
    // https://github.com/jacobslusser/JwtAuthRenewWebApi/blob/master/docs/Sliding-Expiration.md

    const ALGO = "HS512";

    public static function issue(array $data)
    {
        $config = DI::getDefault()->get('config');

        $payload = array(
            "iss" => $config->jwtIssuer,
            "aud" => $config->jwtIssuer,
            "iat" => time(),
            "nbf" => time(),
            "exp" => time() + (3600 * 24 * getenv('JWT_EXPIRE_DAYS')),
            "data" => $data
        );

        return JWT::encode($payload, $config->jwtKey, self::ALGO);
    }

    public static function parse(string $token)
    {
        $config = DI::getDefault()->get('config');

        try {
            $data = JWT::decode($token, $config->jwtKey, [self::ALGO]);
        } catch (SignatureInvalidException $e) {
            throw new UnauthorizedException();
        } catch (ExpiredException $e) {
            throw new UnauthorizedException();
        }

        return $data;
    }

    /** Phalcon has philosophical issues with parsing headers that do not have HTTP_ prefix
     * https://forum.phalcon.io/discussion/10137/accessing-authorization-custom-request-headers
     */
    public static function getAuthTokenFromHeaders()
    {
        $headers = getallheaders();

        /**
         * Headers should be case INsensitive, as per RFC2616 https://www.w3.org/Protocols/rfc2616/rfc2616.html
         * tldr on: https://stackoverflow.com/a/5259004/7802520
         * todo: should think about refactoring the app to use str_toupper everywhere where working with headers.
        */

        // illiterate cypress framework sends auth header with all small caps
        if(!isset($headers['Authorization']) && isset($headers['authorization'])) {
            $headers['Authorization'] = $headers['authorization'];
        }

        if(isset($headers['Authorization']) && !empty($headers['Authorization'])){
            return str_replace('Bearer ', '', $headers['Authorization']);
        }

        return false;
    }
}
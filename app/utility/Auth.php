<?php

namespace Phapi\Utility;

use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Phapi\Exceptions\UnauthorizedException;

class Auth{

    // Currently implementing Sliding expiration
    // https://github.com/jacobslusser/JwtAuthRenewWebApi/blob/master/docs/Sliding-Expiration.md

    const ALGO = "HS512";
    const EXPIRE_DAYS = 1;

    public static function issue(array $data){
        $config = \Phalcon\DI::getDefault()->get('config');

        $payload = array(
            "iss" => $config->jwtIssuer,
            "aud" => $config->jwtIssuer,
            "iat" => time(),
            "nbf" => time(),
            "exp" => time() + (3600 * 24 * self::EXPIRE_DAYS),
            "data" => $data
        );

        return JWT::encode($payload, $config->jwtKey, self::ALGO);
    }

    public static function parse(string $token){
        $config = \Phalcon\DI::getDefault()->get('config');

        try {
            $data = JWT::decode($token, $config->jwtKey, [self::ALGO]);
        }
        catch (SignatureInvalidException $e){
            throw new UnauthorizedException();
        }
        catch (ExpiredException $e){
            throw new UnauthorizedException();
        }

        return $data;
    }

    /** Phalcon has philosophical issues with parsing headers that do not have HTTP_ prefix
     * https://forum.phalcon.io/discussion/10137/accessing-authorization-custom-request-headers
     */
    public static function getAuthTokenFromHeaders(){
        $headers = getallheaders();
        $token = $headers['Authorization'];

        return str_replace('Bearer ', '', $token);
    }
}
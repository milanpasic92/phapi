<?php

namespace Phapi\Application;

use Phalcon\DI;
use Phalcon\Mvc\Micro;
use Phapi\Exceptions\ApiException;
use Phapi\Exceptions\BaseException;
use Phapi\Exceptions\ForbiddenException;
use Phapi\Exceptions\UnauthorizedException;
use Phapi\Utility\Auth;

class AuthMiddleware
{
    protected Micro $app;
    protected $di;

    public function __construct(Micro $app)
    {
        $this->app = $app;
        $this->di = DI::getDefault();
    }

    /**
     * Checks for Authorization Header, sets ApiUser object to user DI service, and filters roles upon acl list
     * @throws UnauthorizedException
     * @throws ApiException
     * @throws ForbiddenException
     */
    public function beforeExecuteRoute() : bool
    {
        $route = $this->di->get('rest')->request->getURI();
        $publicApiRoutes = $this->di->get('registry')->get('publicApiRoutes');

        if(!$publicApiRoutes){
            throw new ApiException('publicApiRoutes not declared');
        }

        $token = Auth::getAuthTokenFromHeaders();
        $publicRoute = in_array(rtrim(strtok($route, '?'), '/'), $publicApiRoutes);

        if($token){
            try {
                $payload = Auth::parse($token);

                $issuedAt = $payload->iat;
                $expireAt = $payload->exp;
            } catch (BaseException | \UnexpectedValueException $e) {
                if ($publicRoute) {
                    // if cli sent token and that token is not valid, treat it like token is not sent at all
                    return true;
                }
                throw new UnauthorizedException();
            }

            if(getenv('ENABLE_REDIS_BLACKLIST') && $this->isBlacklisted($token)){
                throw new ForbiddenException();
            }

            $tokenValidityInSeconds = $expireAt - $issuedAt;

            if ($issuedAt + ($tokenValidityInSeconds / 2) < time()) {
                // if half the time has passed since the token was issued, re-issue new token for same user
                $token = Auth::issue((array)$payload->data);
            }

            $apiUser = new ApiUser((array)$payload->data, $token);
            $this->di->set('user', $apiUser);

            if ($publicRoute) {
                return true;
            }
            return $this->aclAllows($apiUser);
        }

        if ($publicRoute) {
            // if public route
            return true;
        }
        throw new UnauthorizedException();
    }

    private function isBlacklisted($token) : bool
    {
        $cache = $this->di->get('redis');

        if(!$cache->exists('jwt_blacklist')){
            return false;
        }

        $blacklist = json_decode($cache->get('jwt_blacklist'));
        return in_array($token, $blacklist);
    }

    /**
     * @throws ForbiddenException
    */
    protected function aclAllows($apiUser) : bool
    {
        $arrHandler = $this->app->getActiveHandler();
        $controllerArr = explode('Controllers\\', $arrHandler[0]->getDefinition());
        $controllerName = end($controllerArr);

        if(!isset($apiUser->data['role'])){
            throw new ForbiddenException('JWT invalid: role claim is missing.');
        }

        $isAllowed = $this->di->get('acl')->acl->isAllowed($apiUser->data['role'], $controllerName, $arrHandler[1]);

        if (!$isAllowed) {
            throw new ForbiddenException();
        }
        return true;
    }
}
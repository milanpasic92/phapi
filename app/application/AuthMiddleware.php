<?php

namespace Phapi\Application;
;
use Phalcon\Mvc\Micro;
use Phapi\Exceptions\BaseException;
use Phapi\Exceptions\ForbiddenException;
use Phapi\Exceptions\UnauthorizedException;
use Phapi\Utility\Auth;

class AuthMiddleware
{
    private Micro $app;
    private $di;

    public function __construct(Micro $app)
    {
        $this->app = $app;
        $this->di = \Phalcon\DI::getDefault();
    }

    /**
     * Checks for Authorization Header, sets ApiUser object to user DI service, and filters roles upon acl list
     * @throws UnauthorizedException
     */
    public function beforeExecuteRoute()
    {
        $route = $this->di->get('rest')->request->getURI();

        if(in_array($route, $this->di->get('registry')->get('publicApiRoutes'))){
            return true;
        }

        $token = Auth::getAuthTokenFromHeaders();
        $payload = Auth::parse($token);

        try {
            $issuedAt = $payload->iat;
            $expireAt = $payload->exp;
        }
        catch (BaseException $e){
            throw new UnauthorizedException();
        }

        $tokenValidityInSeconds = $expireAt - $issuedAt;

        if($issuedAt + ($tokenValidityInSeconds / 2) > time()){
            // if half the time has passed since the token was issued, re-issue new token for same user
            $token = Auth::issue((array) $payload->data);
        }

       $apiUser = new ApiUser((array) $payload->data, $token);
       $this->di->set('user', $apiUser);

       return $this->aclAllows($apiUser);
    }

    private function aclAllows($apiUser){
        $arrHandler = $this->app->getActiveHandler();
        $controllerArr = explode('Controllers\\', $arrHandler[0]->getDefinition());
        $controllerName = end($controllerArr);

        $isAllowed = $this->di->get('acl')->acl->isAllowed($apiUser->data['role'], $controllerName, $arrHandler[1]);

        if(!$isAllowed){
            throw new ForbiddenException();
        }
        return true;
    }
}
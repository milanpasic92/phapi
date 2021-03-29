<?php

namespace Phapi\Controllers;

use Phapi\Application\ApiResponse;
use Phapi\Application\ApiUser;
use Phapi\Utility\Auth;

class IdentityController extends BaseController
{
    /**
     * Login
     *
     * @return ApiResponse
     */
    public function loginAction()
    {
        $matchedUser = $this->rest->request->getPost();

        $token = Auth::issue($matchedUser);
        $this->di->set('user', new ApiUser($matchedUser, $token));

        // ApiResponse object will embed access token within `meta` part of the response
        return new ApiResponse('success');
    }

    /**
     * Password reset
     *
     * @return ApiResponse
     */
    public function passwordResetAction()
    {
        return new ApiResponse('ce bude');
    }
}
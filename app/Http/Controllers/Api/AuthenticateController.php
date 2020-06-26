<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\AuthenticationFailed;
use App\Http\Controllers\Concerns\AuthenticatesUser;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class AuthenticateController extends Controller
{
    /**
     * Fires the authentication (login) action to produce a token for the frontend app
     *
     * @param AuthenticatesUser $auth
     * @return Application|ResponseFactory|Response
     * @throws AuthenticationFailed
     * @throws ValidationException
     */
    public function login(AuthenticatesUser $auth)
    {
        $token = $auth->loginUser();

        return response([
            'data' => $token
        ]);
    }
}

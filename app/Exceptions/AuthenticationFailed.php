<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Support\Responsable;

class AuthenticationFailed extends Exception implements Responsable
{

    public function toResponse($request)
    {
        return response([
            'error' => 'Unable to authenticate the user'
        ], 401);
    }
}

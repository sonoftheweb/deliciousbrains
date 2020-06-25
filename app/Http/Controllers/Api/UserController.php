<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Traits\ModelRelationshipBinding;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use ModelRelationshipBinding;

    protected $relationship_dependencies = [
        'details' => [
            'profile'
        ],
        'account-details' => [
            'accountBalance',
            'accountActivity'
        ]
    ];


    /**
     * Get singular user data
     *
     * @param $id
     * @return UserResource
     */
    public function show($id)
    {
        $id = ($id === 'me') ? Auth::id() : $id;

        $user = $this->buildRelationshipsToLoad(request(), User::query()->where('id', $id));

        return new UserResource($user->first());
    }
}

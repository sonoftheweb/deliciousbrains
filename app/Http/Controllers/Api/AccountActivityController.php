<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Concerns\HandlesAccountActivity;
use App\Http\Controllers\Controller;
use App\Models\AccountActivity;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class AccountActivityController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param HandlesAccountActivity $accountActivity
     * @return Response
     * @throws ValidationException
     */
    public function store(HandlesAccountActivity $accountActivity)
    {
        $accountActivity->saveAccountActivity();

        return response([
            'messages' => 'Operation successful'
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param HandlesAccountActivity $activity
     * @param AccountActivity $accountActivity
     * @return void
     * @throws ValidationException
     */
    public function update(HandlesAccountActivity $activity, AccountActivity $accountActivity)
    {
        $activity->updateActivity($accountActivity);
    }
}

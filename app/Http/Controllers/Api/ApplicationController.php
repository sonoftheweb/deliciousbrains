<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function index()
    {
        $data = [
            'is_authenticated' => !Auth::guest()
        ];

        $data['debug'] = env('APP_DEBUG');

        $data['env'] = env('APP_ENV');

        return $data;
    }
}

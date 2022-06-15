<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Helper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUser(Request $request) :JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $request->user()->load('posts');

        return Helper::sendResponse($user);
    }
}

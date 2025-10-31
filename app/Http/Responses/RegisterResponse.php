<?php

namespace App\Http\Responses;

use App\Http\Resources\UserResource;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        return ApiResponse::success(
            data: ['user' => new UserResource($request->user())],
            message: 'Registration successful',
            statusCode: 201
        );
    }
}

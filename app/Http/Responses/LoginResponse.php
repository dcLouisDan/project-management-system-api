<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        return ApiResponse::success(
            data: [
                'two_factor' => false,
                'user' => $request->user(),
            ],
            message: 'Login successful'
        );
    }
}

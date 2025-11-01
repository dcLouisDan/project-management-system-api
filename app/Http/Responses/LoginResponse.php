<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        return ApiResponse::success(
            data: [
                'two_factor' => false,
                'user' => Auth::user(),
            ],
            message: 'Login successful'
        );
    }
}

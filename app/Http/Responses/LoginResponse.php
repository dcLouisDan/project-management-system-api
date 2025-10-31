<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Log;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        Log::info('User logged in', ['user_id' => $request->user()->id]);

        return ApiResponse::success(
            data: [
                'two_factor' => false,
                'user' => $request->user(),
            ],
            message: 'Login successful'
        );
    }
}

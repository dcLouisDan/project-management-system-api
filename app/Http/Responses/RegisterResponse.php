<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
  public function toResponse($request)
  {
    return ApiResponse::success(
      data: ['user' => $request->user()],
      message: 'Registration successful',
      statusCode: 201
    );
  }
}

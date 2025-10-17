<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

class ApiResponse implements Responsable
{
  public function __construct(
    private mixed $data = null,
    private ?string $message = null,
    private int $statusCode = 200,
    private array $meta = [],
    private ?array $errors = null,
  ) {}

  public static function success(
    mixed $data = null,
    ?string $message = null,
    int $statusCode = 200,
    array $meta = []
  ): self {
    return new self($data, $message, $statusCode, $meta);
  }

  public static function error(
    ?string $message = null,
    int $statusCode = 400,
    ?array $errors = null,
    mixed $data = null,
    array $meta = []
  ): self {
    return new self($data, $message, $statusCode, $meta, $errors);
  }

  public function toResponse($request): JsonResponse
  {
    $meta = [
      'success' => $this->statusCode >= 200 && $this->statusCode < 300,
      'timestamp' => now()->toIso8601String(),
    ];

    if (!empty($this->meta)) {
      $meta = array_merge($meta, $this->meta);
    }

    $response = ['meta' => $meta];

    if ($this->message !== null) {
      $response['message'] = $this->message;
    }

    if ($this->data !== null) {
      $response['data'] = $this->data;
    }

    if ($this->errors !== null) {
      $response['errors'] = $this->errors;
    }

    return response()->json($response, $this->statusCode);
  }
}

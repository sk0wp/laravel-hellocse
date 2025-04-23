<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ApiException extends Exception
{
    public function render($request): JsonResponse
    {
        return response()->json([
            'error' => true,
            'message' => $this->getMessage() ?: 'Erreur API',
        ], $this->getCode() ?: 400);
    }
}

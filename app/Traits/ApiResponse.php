<?php

namespace App\Traits;

use InfyOm\Generator\Utils\ResponseUtil;

trait ApiResponse
{
    public function sendResponse(mixed $result, string $message, $code = 200)
    {
        return response()->json(ResponseUtil::makeResponse($message, $result), $code);
    }

    public function sendError(string $error, $code = 404)
    {
        return response()->json(ResponseUtil::makeError($error), $code);
    }

    public function sendErrorWithData(string $error, array $data, $code = 404)
    {
        return response()->json(ResponseUtil::makeError($error, $data), $code);
    }

    public function sendSuccess(string $message)
    {
        return response()->json([
            'success' => true,
            'message' => $message
        ], 200);
    }
}

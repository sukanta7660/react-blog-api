<?php

use msztorc\LaravelEnv\Env;

class Helper
{

    public const DEFAULT_OK_MESSAGE = 'Data successfully fetched';
    public const DEFAULT_FORBIDDEN_MESSAGE = 'Unauthorized access';

    static function formatResponse(mixed $data = [], string $message = self::DEFAULT_OK_MESSAGE, bool $status = true, int $statusCode = \Illuminate\Http\Response::HTTP_OK): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'data'    => $data,
            'message' => $message,
            'status'  => $status,
        ], $statusCode);
    }

    static function sendResponse(mixed $data = [], string $message = self::DEFAULT_OK_MESSAGE, bool $status = true, int $statusCode = \Illuminate\Http\Response::HTTP_OK): \Illuminate\Http\JsonResponse
    {
        return self::formatResponse($data, $message, $status, $statusCode);
    }

    static function sendForbiddenResponse($message = self::DEFAULT_FORBIDDEN_MESSAGE): \Illuminate\Http\JsonResponse
    {
        return self::formatResponse([], $message, false, \Illuminate\Http\Response::HTTP_FORBIDDEN);
    }

    static function env(): Env
    {
        return (new Env());
    }

    static function setEnv(string $key, string $value, bool $write = true): string
    {
        return self::env()->setValue($key, $value, $write);
    }

    static function getEnv(string $key): string
    {
        return self::env()->getValue($key);
    }

    static function deleteEnv($key): bool
    {
        return self::env()->deleteVariable($key);
    }

    static function flashCache ($expectJson = false): array|\Illuminate\Http\JsonResponse
    {
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');

        $data = [
            'message' => 'Route, Config, Cache Cleared',
            'status' => 'success'
        ];

        if($expectJson) {
            return self::sendResponse([],  $data['message']);
        }

        return $data;
    }

}

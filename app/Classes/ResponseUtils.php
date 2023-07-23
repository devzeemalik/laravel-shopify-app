<?php

namespace App\Classes;

/**
 * Use this trate for language related utility methods
 */
class ResponseUtils
{
    public static function unauth()
    {
        return response()->json("Unauthorized", 401);
    }

    public static function failed($message = "Failed")
    {
        return response()->json($message, 500);
    }

    public static function badRequest($message = 'Bad Request')
    {
        return response()->json($message, 400);
    }

    public static function alreadyExists($message = 'Already exists')
    {
        return response()->json($message, 403);
    }

    public static function notFound()
    {
        return response()->json("Not Found", 404);
    }

    public static function notExist($message = 'No Content')
    {
        return response()->json($message, 204);
    }

    public static function noContent($message = 'No Content')
    {
        return static::notExist($message);
    }

    public static function created()
    {
        return response()->json('Created', 201);
    }

    public static function updated()
    {
        return response()->json('', 204);
    }

    public static function saved()
    {
        return response()->json('', 204);
    }

    public static function deleted()
    {
        return response()->json('Updated', 204);
    }

    public static function success($message = "OK")
    {
        return response()->json($message, 200);
    }

    public static function ok($message = "OK")
    {
        return static::success($message);
    }

    public static function invalidInput($message = "Invalid input")
    {
        return response()->json($message, 422);
    }

    public static function truthly($message = 'true')
    {
        return response()->json($message, 200);
    }

    public static function falsy($message = 'false')
    {
        return response()->json($message, 204);
    }

    public static function noChange()
    {
        return response()->json(null, 304);
    }
}

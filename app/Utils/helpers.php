<?php

if (!function_exists("json_response")) {
    /**
     * wrapper for return response json
     * 
     * @param string $message
     * @param int $code
     * @param interface $data
     * @param string $status
     * 
     * @return Response $response
     */
    function json_response(string $message, int $code = 200, $data = [])
    {
        return response()->json([
            "status" => ($code >= 200 && $code < 300) ? "success" : "failed",
            "message" => $message,
            "data" => $data,
        ], $code);
    }
}

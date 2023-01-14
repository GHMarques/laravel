<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Send server error
     *
     * @param Throwable, string, string
     * @return Response
     */
    public function sendServerError($exception, $class = '', $function = '')
    {
        Log::error($class . '::' . $function . ', ' . $exception->getMessage()
            . ' The exception was thrown on line: ' .
            $exception->getLine());
        $error = [
            'error' => trans('common.bad_request')
        ];
        $statusCode = Response::HTTP_BAD_REQUEST;
        return response()->json($error, $statusCode);
    }
}

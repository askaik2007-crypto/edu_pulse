<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RequestLoggerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);

        // تنفيذ الطلب والانتقال للخطوة التالية
        $response = $next($request);

        $executionTime = round((microtime(true) - $startTime) * 1000, 2);

     // تسجيل الطلب باللغة العربية
        Log::info('طلب HTTP وارد', [
            'method'            => $request->method(),
            'url'               => $request->fullUrl(),
            'execution_time_ms' => $executionTime . ' ms',
            'status_code'        => $response->getStatusCode(),
            'ip'                => $request->ip(),
        ]);

        return $response;
    }
}
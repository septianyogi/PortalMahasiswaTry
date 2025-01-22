<?php

namespace App\Http\Middleware;

use Barryvdh\Debugbar\Facades\Debugbar;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DebugbarHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (app()->bound('debugbar') && app('debugbar')->isEnabled()) {
            $debugData = Debugbar::getData();

            // Tambahkan jumlah query dan waktu eksekusi ke header
            $response->headers->set('Debugbar-Queries', count($debugData['queries']['statements'] ?? []));
            $response->headers->set('Debugbar-Time', $debugData['time']['duration_str'] ?? 'N/A');
            $response->headers->set('Debugbar-Memory', $debugData['memory']['peak_usage_str'] ?? 'N/A');
        }
        return $response;
    }
}

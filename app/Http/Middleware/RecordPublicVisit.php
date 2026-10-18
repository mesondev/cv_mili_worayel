<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RecordPublicVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->isMethod('GET') && $response->isSuccessful() && str_contains((string) $response->headers->get('Content-Type'), 'text/html')) {
            try {
                DB::table('visitor_logs')->insert([
                    'ip_address' => $this->anonymizeIp($request->ip()),
                    'path' => $request->route()?->getName() ?? '/',
                    'referrer' => null,
                    'user_agent' => null,
                    'visited_at' => now(),
                ]);
            } catch (Throwable $exception) {
                report($exception);
            }
        }

        return $response;
    }

    private function anonymizeIp(?string $ip): ?string
    {
        if (! $ip) {
            return null;
        }

        $packed = inet_pton($ip);
        if ($packed === false) {
            return null;
        }

        $prefixLength = strlen($packed) === 4 ? 3 : 8;
        $anonymized = substr($packed, 0, $prefixLength).str_repeat("\0", strlen($packed) - $prefixLength);

        return inet_ntop($anonymized) ?: null;
    }
}

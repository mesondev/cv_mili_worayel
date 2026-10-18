<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminModuleAccess
{
    public function handle(Request $request, Closure $next, string $action = 'view'): Response
    {
        $module = $request->route('module');
        $user = $request->user();

        if (is_string($module) && ! in_array($module, config('admin.modules', []), true)) {
            return $next($request);
        }

        abort_unless(
            $user && is_string($module) && $user->canAccessAdminModule($module, $action),
            403,
            'Anda tidak memiliki izin untuk mengakses modul atau melakukan tindakan ini.'
        );

        return $next($request);
    }
}

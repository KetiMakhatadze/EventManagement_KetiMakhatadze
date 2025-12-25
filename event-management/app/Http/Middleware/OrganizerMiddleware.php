<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OrganizerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || (!auth()->user()->isAdmin() && !auth()->user()->isOrganizer())) {
            abort(403, 'ეს გვერდი ხელმისაწვდომია მხოლოდ ორგანიზატორებისთვის.');
        }

        return $next($request);
    }
}
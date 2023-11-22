<?php

namespace App\Http\Middleware;

// app/Http/Middleware/CheckUserRole.php

use Closure;
use App\Models\User;

class CheckUserRole
{
    public function handle($request, Closure $next)
    {
        $user = User::find(1);

        if ($user->hasRole('admin')) {
            // do something
        }

        if ($user->can('view users')) {
            // do something
        }

        return $next($request);
    }
}

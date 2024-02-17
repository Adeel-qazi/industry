<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPackage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $package = auth()->user()->subscriptions()->orderByDesc('id')->first();

        if ($package) {
            $createdAt = Carbon::parse($package->pivot->created_at);
            $today = Carbon::parse(now());
            switch ($package->duration_unit) {
                case 'weeks':
                    $unit = 'addWeeks';
                    break;
                case 'months':
                    $unit = 'addMonths';
                    break;
                case 'years':
                    $unit = 'addYears';
                    break;
            }
            $expire = $createdAt->$unit($package->duration);
            if (!$today->gt($expire)) return redirect()->route('user.profile');
        }
        return $next($request);
    }
}

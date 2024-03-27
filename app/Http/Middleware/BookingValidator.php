<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BookingValidator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'room_id' => 'required|exists:rooms,id',
                'customer' => 'required|string|max:100',
                'guests' => 'required|integer',
                'start' => 'required|date',
                'end' => 'required|date',
            ]);
        } elseif ($request->isMethod('get')) {
            $request->validate([
                'room_id' => 'exists:rooms,id'
            ]);
        }

        return $next($request);
    }
}

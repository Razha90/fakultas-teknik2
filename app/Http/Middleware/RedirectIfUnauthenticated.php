<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectIfUnauthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            // Jika bukan di /login, tampilkan peringatan
            if ($request->path() !== 'login') {
                session()->flash('error', 'Terdeteksi mencoba mengakses URL lain.');
                // Kirim event untuk menampilkan SweetAlert
                $request->session()->flash('show_alert', true);
                return redirect()->route('login');
            }
        }


        return $next($request);
    }
}

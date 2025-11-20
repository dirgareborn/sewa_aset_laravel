<?php

namespace App\Http\Middleware;

use App\Models\Visitor;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CountVisitor
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->session()->has('visitor_counted')) {
            $ip = $request->ip();
            $userAgent = $request->userAgent();

            // Menggunakan free API: ip-api.com
            $city = null;
            $region = null;

            try {
                $response = Http::get("http://ip-api.com/json/{$ip}?fields=status,regionName,city");
                if ($response->successful() && $response['status'] === 'success') {
                    $city = $response['city'] ?? null;
                    $region = $response['regionName'] ?? null;
                }
            } catch (\Exception $e) {
                // fallback jika API gagal
            }

            Visitor::create([
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'city' => $city,
                'region' => $region,
            ]);

            $request->session()->put('visitor_counted', true);
        }

        return $next($request);
    }
}

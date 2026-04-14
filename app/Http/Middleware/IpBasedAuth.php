<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\IpMapping;
use Illuminate\Support\Facades\Auth;

class IpBasedAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $clientIp = $request->ip();

        // Check if IP is in the allowed subnet (192.168.100.x)
        if (!$this->isAllowedSubnet($clientIp)) {
            return $next($request);
        }

        // Check if user has manually logged out in this session
        if ($request->session()->get('manual_logout')) {
            return $next($request);
        }

        // Check if user is already authenticated
        if (Auth::check()) {
            $user = Auth::user();

            // If user is IP-based, verify their IP mapping is still active
            if ($user->isIpBasedUser()) {
                $ipMapping = IpMapping::where('ip_address', $clientIp)
                    ->where('is_active', true)
                    ->first();

                // If IP mapping is inactive or doesn't exist, log out the user
                if (!$ipMapping) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect()->route('login')->with('error', 'IP-based access has been disabled.');
                }

                // Update last used timestamp
                $ipMapping->updateLastUsed();
            }

            return $next($request);
        }

        // Try to find an active IP mapping for this IP
        $ipMapping = IpMapping::where('ip_address', $clientIp)
            ->where('is_active', true)
            ->first();

        if ($ipMapping) {
            // Get or create the associated user
            $user = $ipMapping->getOrCreateUser();

            // Log the user in
            Auth::login($user, true); // Remember the user

            // Update last used timestamp
            $ipMapping->updateLastUsed();
        }

        return $next($request);
    }

    /**
     * Check if IP is in the allowed subnet (192.168.100.x).
     *
     * @param  string  $ip
     * @return bool
     */
    private function isAllowedSubnet($ip)
    {
        return IpMapping::validateIpFormat($ip);
    }
}

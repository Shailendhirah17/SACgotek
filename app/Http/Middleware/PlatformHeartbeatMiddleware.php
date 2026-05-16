<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\PlatformMonitoringService;

class PlatformHeartbeatMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Execute the request first so we don't slow down the response time,
        // ideally heartbeat would be dispatched as a queued job to avoid any latency,
        // but for simplicity in this implementation, we do it inline or via terminate.
        
        $response = $next($request);

        // We only ping if the user is authenticated and part of a school
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if user belongs to a school (most users in this SaaS do)
            if ($user && isset($user->school_id)) {
                
                // Let's only ping once every 5 minutes per session to avoid DB hammering
                $lastPing = session('last_heartbeat_ping');
                if (!$lastPing || now()->diffInMinutes($lastPing) >= 5) {
                    try {
                        $monitoringService = new PlatformMonitoringService();
                        
                        // We also need school_group_id if available, though it might natively reside
                        // on the SmSchool model. The service finds it or updates it.
                        // For this implementation we'll pass null and let the service handle relationships if needed.
                        $monitoringService->pingHeartbeat($user->school_id);
                        
                        session(['last_heartbeat_ping' => now()]);
                    } catch (\Exception $e) {
                        // Fail silently so we don't break the application if heartbeat fails
                        \Illuminate\Support\Facades\Log::error('Heartbeat Ping Failed: ' . $e->getMessage());
                    }
                }
            }
        }

        return $response;
    }
}

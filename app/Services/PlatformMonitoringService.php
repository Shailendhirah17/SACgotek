<?php

namespace App\Services;

use App\Models\SchoolHeartbeat;
use App\SmSchool;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PlatformMonitoringService
{
    /**
     * Ping a heartbeat for a specific school.
     * This should ideally be called via a middleware or scheduled task.
     * 
     * @param int $schoolId
     * @param int|null $schoolGroupId
     * @return void
     */
    public function pingHeartbeat($schoolId, $schoolGroupId = null)
    {
        $heartbeat = SchoolHeartbeat::firstOrNew(['school_id' => $schoolId]);
        
        $heartbeat->school_group_id = $schoolGroupId;
        $heartbeat->last_activity_at = now();
        
        // Simple heuristic for daily active users (DAU) update.
        // In a real system, you'd calculate this from active sessions or access logs.
        $heartbeat->daily_active_users = DB::table('users')
            ->where('school_id', $schoolId)
            ->where('updated_at', '>=', now()->subDay())
            ->count();
            
        // Calculate health status based on activity
        $heartbeat->health_status = $this->calculateHealthStatus($heartbeat->last_activity_at);
        
        // Calculate churn risk based on activity patterns
        $heartbeat->churn_risk_score = $this->calculateChurnRisk($heartbeat->last_activity_at, $heartbeat->daily_active_users);

        $heartbeat->save();
    }

    /**
     * Identify at-risk schools across the platform.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAtRiskSchools()
    {
        return SchoolHeartbeat::with('school', 'schoolGroup')
            ->where('churn_risk_score', '>=', 0.7) // 70% or higher risk
            ->orWhere('health_status', 'at-risk')
            ->orderBy('churn_risk_score', 'desc')
            ->get();
    }

    /**
     * Calculate health status based on recency of activity.
     * 
     * @param \Carbon\Carbon|null $lastActivity
     * @return string
     */
    private function calculateHealthStatus(?Carbon $lastActivity)
    {
        if (!$lastActivity) {
            return 'inactive';
        }

        $daysInactive = $lastActivity->diffInDays(now());

        if ($daysInactive <= 2) {
            return 'good';
        } elseif ($daysInactive <= 7) {
            return 'at-risk';
        } else {
            return 'inactive';
        }
    }

    /**
     * Heuristic churn prediction algorithm.
     * Higher score (closer to 1.0) means higher risk of churn.
     * 
     * @param \Carbon\Carbon|null $lastActivity
     * @param int $dau
     * @return float
     */
    private function calculateChurnRisk(?Carbon $lastActivity, int $dau)
    {
        $score = 0.0;

        if (!$lastActivity) {
            return 1.0; // Max risk if no activity ever recorded
        }

        $daysInactive = $lastActivity->diffInDays(now());

        // Factor 1: Recency of activity (up to 0.6 of the score)
        if ($daysInactive > 14) {
            $score += 0.6;
        } elseif ($daysInactive > 7) {
            $score += 0.4;
        } elseif ($daysInactive > 3) {
            $score += 0.2;
        }

        // Factor 2: Daily Active Users (up to 0.4 of the score)
        // If DAU drops to very low numbers, risk goes up
        if ($dau === 0 && $daysInactive > 1) {
            $score += 0.4;
        } elseif ($dau > 0 && $dau < 10) {
           $score += 0.2; // Minor risk for very low engagement 
        }

        return min(max($score, 0.0), 1.0); // Clamp between 0.0 and 1.0
    }
}

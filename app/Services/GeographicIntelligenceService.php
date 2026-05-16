<?php

namespace App\Services;

use App\SmSchool;
use App\Models\SchoolGroup;
use Illuminate\Support\Facades\DB;

class GeographicIntelligenceService
{
    /**
     * Get aggregated geographic intelligence data for the platform.
     * 
     * @return array
     */
    public function getPlatformGeoData()
    {
        // 1. Total Schools with location data
        $schoolsWithLocation = SmSchool::whereNotNull('latitude')
                                       ->whereNotNull('longitude')
                                       ->where('active_status', 1)
                                       ->get(['id', 'school_name', 'latitude', 'longitude', 'state_id', 'city_id', 'school_group_id']);

        // 2. State-wise distribution
        $stateDistribution = [];
        if (DB::getSchemaBuilder()->hasTable('sm_states')) {
            $stateDistribution = DB::table('sm_schools')
                ->join('sm_states', 'sm_schools.state_id', '=', 'sm_states.id')
                ->select('sm_states.name as state_name', DB::raw('count(sm_schools.id) as total_schools'))
                ->where('sm_schools.active_status', 1)
                ->groupBy('sm_states.name')
                ->orderBy('total_schools', 'desc')
                ->get();
        }

        // 3. City-wise distribution
        $cityDistribution = [];
        if (DB::getSchemaBuilder()->hasTable('sm_cities')) {
            $cityDistribution = DB::table('sm_schools')
                ->join('sm_cities', 'sm_schools.city_id', '=', 'sm_cities.id')
                ->select('sm_cities.name as city_name', DB::raw('count(sm_schools.id) as total_schools'))
                ->where('sm_schools.active_status', 1)
                ->groupBy('sm_cities.name')
                ->orderBy('total_schools', 'desc')
                ->get();
        }

        // 4. Underserved areas / Growth Opportunities (States with 0 or low schools)
        $growthOpportunities = [];
        if (DB::getSchemaBuilder()->hasTable('sm_states')) {
            $growthOpportunities = DB::table('sm_states')
                ->leftJoin('sm_schools', 'sm_states.id', '=', 'sm_schools.state_id')
                ->select('sm_states.name', DB::raw('count(sm_schools.id) as school_count'))
                ->groupBy('sm_states.id', 'sm_states.name')
                ->havingRaw('school_count < ?', [5]) // E.g., states with fewer than 5 schools
                ->orderBy('school_count', 'asc')
                ->get();
        }

        return [
            'map_points' => $schoolsWithLocation,
            'state_distribution' => $stateDistribution,
            'city_distribution' => $cityDistribution,
            'growth_opportunities' => $growthOpportunities,
        ];
    }

    /**
     * Get geographic intelligence data for a specific School Group.
     * 
     * @param int $schoolGroupId
     * @return array
     */
    public function getGroupGeoData($schoolGroupId)
    {
        $schoolsWithLocation = SmSchool::where('school_group_id', $schoolGroupId)
                                       ->whereNotNull('latitude')
                                       ->whereNotNull('longitude')
                                       ->where('active_status', 1)
                                       ->get(['id', 'school_name', 'latitude', 'longitude']);

        return [
            'map_points' => $schoolsWithLocation,
        ];
    }
}

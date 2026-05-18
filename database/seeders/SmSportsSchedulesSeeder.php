<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\SmSportsSchedule;
use Carbon\Carbon;

class SmSportsSchedulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate existing schedules to start fresh
        SmSportsSchedule::truncate();

        $teamSports = [
            'Football' => [
                ['title' => 'Varsity Football Match Practice', 'venue' => 'Main Stadium Field 1', 'offset_hours' => 1],
                ['title' => 'Football Tactical & Formation Training', 'venue' => 'Practice Field A', 'offset_hours' => 24],
                ['title' => 'Football Physical Conditioning', 'venue' => 'Campus Gym & Fitness Center', 'offset_hours' => 48],
            ],
            'Cricket' => [
                ['title' => 'Net Batting & Bowling Session', 'venue' => 'Cricket Nets Ground', 'offset_hours' => 2],
                ['title' => 'Cricket Fielding & Catching Drills', 'venue' => 'Main Cricket Oval', 'offset_hours' => 26],
            ],
            'Basketball' => [
                ['title' => 'Basketball Strategy & Free Throws', 'venue' => 'Indoor Sports Complex Court 1', 'offset_hours' => 1],
                ['title' => 'Basketball Defensive Play Drills', 'venue' => 'Indoor Sports Complex Court 1', 'offset_hours' => 28],
            ],
            'Volleyball' => [
                ['title' => 'Volleyball Spiking & Serving Drills', 'venue' => 'Outdoor Volleyball Courts', 'offset_hours' => 3],
                ['title' => 'Volleyball Team Coordination Play', 'venue' => 'Indoor Arena Court 2', 'offset_hours' => 30],
            ],
            'Hockey' => [
                ['title' => 'Hockey Penalty Corner Drills', 'venue' => 'Turf Ground B', 'offset_hours' => 4],
                ['title' => 'Hockey Speed & Endurance Drill', 'venue' => 'Turf Ground B', 'offset_hours' => 32],
            ],
            'Rugby' => [
                ['title' => 'Rugby Scrum & Tactical Strategy', 'venue' => 'Rugby Ground A', 'offset_hours' => 5],
            ],
            'Baseball' => [
                ['title' => 'Baseball Batting Cage Session', 'venue' => 'Baseball Pitching Area', 'offset_hours' => 6],
            ],
            'Softball' => [
                ['title' => 'Softball Team Scrimmage Match', 'venue' => 'Softball Arena', 'offset_hours' => 7],
            ],
            'Handball' => [
                ['title' => 'Handball Fastbreak & Shooting Play', 'venue' => 'Multi-Sport Indoor Court', 'offset_hours' => 8],
            ],
            'Water Polo' => [
                ['title' => 'Water Polo Swimming & Goal Drills', 'venue' => 'Olympic Swimming Pool Complex', 'offset_hours' => 9],
            ]
        ];

        foreach ($teamSports as $sport => $sessions) {
            foreach ($sessions as $session) {
                // Calculate date and time using Carbon
                $targetTime = Carbon::now()->addHours($session['offset_hours']);
                
                SmSportsSchedule::create([
                    'sport_name' => $sport,
                    'title' => $session['title'],
                    'session_date' => $targetTime->format('Y-m-d'),
                    'session_time' => $targetTime->format('h:i A') . ' - ' . $targetTime->addHours(2)->format('h:i A'),
                    'venue' => $session['venue'],
                    'school_id' => 1,
                    'academic_id' => 1,
                ]);
            }
        }
    }
}

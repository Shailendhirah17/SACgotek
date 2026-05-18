<?php

namespace App\Http\Controllers\Student;

use Exception;
use App\SmStudent;
use App\SmStudentSport;
use App\SmSportsSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class SmStudentSportsController extends Controller
{
    // Define the 30 predefined sports
    protected $predefinedSports = [
        'Football', 'Cricket', 'Basketball', 'Volleyball', 'Hockey', 
        'Rugby', 'Baseball', 'Softball', 'Handball', 'Water Polo', 
        'Badminton', 'Tennis', 'Table Tennis', 'Squash', 'Golf', 
        'Athletics', 'Swimming', 'Gymnastics', 'Archery', 'Shooting', 
        'Cycling', 'Boxing', 'Wrestling', 'Karate', 'Taekwondo', 
        'Judo', 'Fencing', 'Rowing', 'Sailing', 'Equestrian'
    ];

    // Define which sports are team-based
    protected $teamSports = [
        'Football', 'Cricket', 'Basketball', 'Volleyball', 'Hockey', 
        'Rugby', 'Baseball', 'Softball', 'Handball', 'Water Polo'
    ];

    /**
     * Display the student sports feature page.
     */
    public function index()
    {
        try {
            $student = Auth::user()->student;
            
            // Fetch any existing selection
            $selectedSport = SmStudentSport::where('student_id', $student->id)->first();
            
            $predefinedSports = $this->predefinedSports;
            $teamSports = $this->teamSports;
            $schedules = collect();
            $isTeamSport = false;

            if ($selectedSport) {
                // Determine if selected sport is in our team-based sports array
                // (Matches regardless of capitalization)
                $isTeamSport = in_array(ucfirst(strtolower($selectedSport->sport_name)), array_map('ucfirst', array_map('strtolower', $this->teamSports)));
                
                if ($isTeamSport) {
                    // Fetch dynamic schedules
                    $schedules = SmSportsSchedule::where('sport_name', $selectedSport->sport_name)
                        ->orderBy('session_date', 'asc')
                        ->get();
                }
            }

            return view('backEnd.studentPanel.sports', compact('student', 'selectedSport', 'predefinedSports', 'teamSports', 'isTeamSport', 'schedules'));
        } catch (Exception $e) {
            Toastr::error('Something went wrong: ' . $e->getMessage(), 'Failed');
            return redirect()->back();
        }
    }

    /**
     * Store or update the student's sports selection.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sport_name' => 'required|string',
            'custom_sport_name' => 'required_if:sport_name,Custom|nullable|string|max:100',
        ], [
            'custom_sport_name.required_if' => 'Please enter a custom sport name.',
        ]);

        try {
            $student = Auth::user()->student;
            $sportName = $request->sport_name;
            $isCustom = 0;

            if ($sportName === 'Custom') {
                $sportName = strip_tags(trim($request->custom_sport_name));
                $isCustom = 1;
            }

            // Save or Update the selection
            SmStudentSport::updateOrCreate(
                ['student_id' => $student->id],
                [
                    'sport_name' => $sportName,
                    'is_custom' => $isCustom,
                    'school_id' => Auth::user()->school_id,
                    'academic_id' => getAcademicId(),
                ]
            );

            // Determine if the newly selected sport is team-based
            $isTeamSport = in_array(ucfirst(strtolower($sportName)), array_map('ucfirst', array_map('strtolower', $this->teamSports)));

            if ($isTeamSport) {
                Toastr::success("Successfully selected $sportName. You are associated with the team schedule!", 'Success');
            } else {
                Toastr::success("Successfully selected $sportName. Individual practice schedule is active.", 'Success');
            }

            return redirect()->back();
        } catch (Exception $e) {
            Toastr::error('Could not save selection: ' . $e->getMessage(), 'Failed');
            return redirect()->back();
        }
    }
}

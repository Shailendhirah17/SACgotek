<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\SmStudent;
use App\SmStudentSport;
use App\SmSportsSchedule;
use App\SmNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendSportsTrainingReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sports:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan upcoming team sports schedules and notify students 1 hour prior to the training session.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Scanning team sports schedules for upcoming training sessions...");
        
        $now = Carbon::now();
        $studentSports = SmStudentSport::all();
        $notificationCount = 0;

        foreach ($studentSports as $selection) {
            $student = $selection->student;
            if (!$student) {
                continue; // Orphan selection record
            }

            // Retrieve active schedules for this student's chosen sport
            $schedules = SmSportsSchedule::where('sport_name', $selection->sport_name)->get();

            foreach ($schedules as $schedule) {
                try {
                    // Extract starting time from time range "04:00 PM - 06:00 PM"
                    $timeParts = explode('-', $schedule->session_time);
                    $startTimeStr = trim($timeParts[0]); // e.g. "04:00 PM"
                    
                    // Parse scheduled datetime
                    $sessionStart = Carbon::parse($schedule->session_date . ' ' . $startTimeStr);
                    
                    // Difference in minutes between session start and now (negative means session is in future)
                    $diffInMinutes = $sessionStart->diffInMinutes($now, false);
                    
                    // Trigger if session starts in approximately 1 hour (between 55 to 65 minutes from now)
                    if ($diffInMinutes >= -65 && $diffInMinutes <= -55) {
                        
                        // Check if we already sent a notification for this schedule to avoid duplicate alerts
                        $exists = SmNotification::where('user_id', $student->user_id)
                            ->where('message', 'like', '%' . $schedule->title . '%')
                            ->exists();

                        if (!$exists) {
                            $message = "Reminder: Your " . $schedule->sport_name . " training session '" . $schedule->title . "' starts in 1 hour at " . $schedule->venue . "!";
                            
                            // 1. Mandatory In-App Notification
                            $notification = new SmNotification();
                            $notification->user_id = $student->user_id;
                            $notification->role_id = 2; // Student role
                            $notification->date = date('Y-m-d');
                            $notification->message = $message;
                            $notification->url = 'student-sports';
                            $notification->school_id = $student->school_id ?? 1;
                            $notification->academic_id = $student->academic_id ?? 1;
                            $notification->save();

                            // 2. Extensible Email / SMS hooks placeholder
                            $this->triggerOptionalAlerts($student, $message);

                            $this->info("=> Dispatched 1-hour training alert to {$student->full_name} [User ID: {$student->user_id}] for '{$schedule->title}'");
                            $notificationCount++;
                        }
                    }
                } catch (\Exception $e) {
                    $this->error("Error checking schedule ID {$schedule->id}: " . $e->getMessage());
                    Log::error("Sports training reminder parsing failed for schedule {$schedule->id}: " . $e->getMessage());
                }
            }
        }

        $this->info("Completed scanning! Sent {$notificationCount} notification(s).");
        return 0;
    }

    /**
     * Trigger optional email and SMS notification integrations.
     */
    protected function triggerOptionalAlerts($student, $message)
    {
        // Placeholder Hook for Email Alert
        if (!empty($student->email)) {
            Log::info("Optional Email Hook triggered for student [{$student->full_name}] ({$student->email}): {$message}");
            // Mail::to($student->email)->send(new \App\Mail\SportsTrainingReminderMail($student, $message));
        }

        // Placeholder Hook for SMS Alert
        if (!empty($student->mobile)) {
            Log::info("Optional SMS Hook triggered for student [{$student->full_name}] ({$student->mobile}): {$message}");
            // send_sms($student->mobile, $message);
        }
    }
}

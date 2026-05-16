<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\SmSchool;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CheckSaaSSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'saas:check-subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks all SaaS tenant schools for expired subscriptions and suspends them.';

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
        $this->info('Starting SaaS Subscription Check...');
        Log::info('Cron: saas:check-subscriptions started.');

        // Exclude the SuperAdmin/Global institution (usually id 1) from being suspended
        $schools = SmSchool::where('active_status', 1)->where('id', '!=', 1)->get();

        $suspendedCount = 0;
        $today = Carbon::today();

        foreach ($schools as $school) {
            if ($school->ending_date && Carbon::parse($school->ending_date)->isPast()) {
                // The subscription is expired
                $school->active_status = 0;
                $school->save();
                
                $suspendedCount++;
                
                Log::info("SaaS: Suspended school #{$school->id} ({$school->school_name}) due to expired subscription on {$school->ending_date}.");
                $this->line("Suspended ID {$school->id}: {$school->school_name}");
            } elseif ($school->ending_date && Carbon::parse($school->ending_date)->diffInDays($today) <= 3) {
                // Approaching expiration (Within 3 days)
                Log::info("SaaS Warning: School #{$school->id} ({$school->school_name}) expiring in less than 3 days.");
                // Here we could trigger a global email alert to the School Admin
            }
        }

        $this->info("Subscription check completed. Suspended {$suspendedCount} school(s).");
        Log::info("Cron: saas:check-subscriptions completed. Suspended {$suspendedCount} schools.");

        return 0;
    }
}

<?php

namespace App\Providers;

use App\Events\ClassTeacherGetAllStudent;
use App\Events\CreateClassGroupChat;
use App\Events\StudentPromotion;
use App\Events\StudentPromotionGroupDisable;
use App\Listeners\ListenClassTeacherGetAllStudent;
use App\Listeners\ListenCreateClassGroupChat;
use App\Listeners\ListenStudentPromotion;
use App\Listeners\ListenStudentPromotionGroupDisable;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {

        parent::boot();

        Event::listen(
            CreateClassGroupChat::class,
            [ListenCreateClassGroupChat::class, 'handle']
        );

        Event::listen(
            ClassTeacherGetAllStudent::class,
            [ListenClassTeacherGetAllStudent::class, 'handle']
        );
        Event::listen(
            StudentPromotion::class,
            [ListenStudentPromotion::class, 'handle']
        );
        Event::listen(
            StudentPromotionGroupDisable::class,
            [ListenStudentPromotionGroupDisable::class, 'handle']
        );

        // ============================================
        // SuperAdmin Events
        // ============================================
        Event::listen(
            \App\Events\SuperAdmin\SuperAdminLoggedIn::class,
            [\App\Listeners\SuperAdmin\LogSuperAdminActivity::class, 'handleLogin']
        );

        Event::listen(
            \App\Events\SuperAdmin\SuperAdminLoggedOut::class,
            [\App\Listeners\SuperAdmin\LogSuperAdminActivity::class, 'handleLogout']
        );

        Event::listen(
            \App\Events\SuperAdmin\SuperAdminUserCreated::class,
            [\App\Listeners\SuperAdmin\LogSuperAdminActivity::class, 'handleUserCreated']
        );

        Event::listen(
            \App\Events\SuperAdmin\SuperAdminUserUpdated::class,
            [\App\Listeners\SuperAdmin\LogSuperAdminActivity::class, 'handleUserUpdated']
        );

        Event::listen(
            \App\Events\SuperAdmin\SuperAdminUserDeleted::class,
            [\App\Listeners\SuperAdmin\LogSuperAdminActivity::class, 'handleUserDeleted']
        );

        Event::listen(
            \App\Events\SuperAdmin\SuperAdminUserStatusChanged::class,
            [\App\Listeners\SuperAdmin\LogSuperAdminActivity::class, 'handleUserStatusChanged']
        );

        Event::listen(
            \App\Events\SuperAdmin\SchoolCreatedBySuperAdmin::class,
            [\App\Listeners\SuperAdmin\LogSuperAdminActivity::class, 'handleSchoolCreated']
        );

        Event::listen(
            \App\Events\SuperAdmin\SchoolDeletedBySuperAdmin::class,
            [\App\Listeners\SuperAdmin\LogSuperAdminActivity::class, 'handleSchoolDeleted']
        );

    }
}

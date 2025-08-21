<?php

namespace App\Providers;

use App\Models\Opportunity;
use App\Policies\OpportunityPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Opportunity::class => OpportunityPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Gate used by AttendanceController@undo
        Gate::define('manage-attendance', function ($user, int $attendanceId) {
            $row = DB::table('attendances as a')
                ->join('opportunities as o', 'o.id', '=', 'a.opportunity_id')
                ->join('organizations as org', 'org.id', '=', 'o.organization_id')
                ->where('a.id', $attendanceId)
                ->select('o.organization_id', 'org.owner_user_id')
                ->first();

            if (!$row) return false;

            // Org owner
            if ((int)$row->owner_user_id === (int)$user->id) return true;

            // Org team member (org_manager)
            $isTeam = DB::table('organization_users')
                ->where('organization_id', $row->organization_id)
                ->where('user_id', $user->id)
                ->exists();

            return $isTeam;
        });

        // In newer Laravel, explicit registerPolicies() not required.
        // $this->registerPolicies();
    }
}

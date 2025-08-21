<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ViewComposerServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Nav dropdown (latest opps)
        View::composer('partials.nav', function ($view) {
            $view->with('navLatestOpportunities',
                \App\Models\Opportunity::orderByDesc('id')->limit(5)->get(['id','title'])
            );
        });

        // Home hero copy
        View::composer('partials.hero-home', function ($view) {
            $view->with('hero', DB::table('settings')->where('key','home.hero')->value('value'));
        });

        // Volunteer settings tabs (counts)
        View::composer('volunteer.settings._tabs', function ($view) {
            $uid = Auth::id();
            $certCount = $hoursSum = $upcoming = 0;
            if ($uid) {
                $certCount = (int) DB::table('certificates')->where('user_id',$uid)->count();
                $hoursSum  = (float) DB::table('volunteer_hours')->where('user_id',$uid)->sum('hours');
                $upcoming  = (int) DB::table('opportunity_applications')->where('user_id',$uid)->count();
            }
            $view->with(compact('certCount','hoursSum','upcoming'));
        });

        // Org partials: inject orgId for the owner
        View::composer([
            'org.partials.dashboard_v1',
            'org.partials.certs_quick',
            'org.partials.recent_activity',
            'org.partials.today_checkins',
            'org.partials.upcoming_7d',
            'org.partials.branding_styles',
            'org.settings._branding_form',
            'org.shortlist._slotcap_form',
            'org.shortlist._counters',
            'org.applicants._filters',
        ], function ($view) {
            $uid = Auth::id();
            $orgId = $uid ? DB::table('organizations')->where('owner_user_id',$uid)->value('id') : null;
            $view->with('vcOrgId', $orgId);
        });
    }
}

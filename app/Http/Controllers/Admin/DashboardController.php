<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Organization;
use App\Models\Opportunity;
use App\Models\Event;

class DashboardController extends Controller
{
    public function index()
    {
        // Lightweight KPIs for now (no charts yet)
        $kpis = [
            'users' => User::count(),
            'organizations' => Organization::count(),
            'opportunities' => Opportunity::count(),
            'events' => Event::count(),
        ];

        // Recent items
        $recentUsers = User::latest()->limit(5)->get();
        $recentOpps  = Opportunity::latest()->limit(5)->get();
        $recentEvents= Event::latest()->limit(5)->get();

        return view('admin.dashboard', compact('kpis', 'recentUsers', 'recentOpps', 'recentEvents'));
    }
}

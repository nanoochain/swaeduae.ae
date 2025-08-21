<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HoursReportController extends Controller
{
    public function show(Request $r, $opportunityId)
    {
        $scopeId = $opportunityId === 'all' ? null : $opportunityId;

        $opportunity = null;
        if ($scopeId) {
            $opportunity = DB::table('opportunities')->where('id', $scopeId)->first();
        }

        // Totals per volunteer
        $totals = DB::table('volunteer_hours as vh')
            ->leftJoin('users as u', 'u.id', '=', 'vh.user_id')
            ->when($scopeId, fn($q) => $q->where('vh.opportunity_id', $scopeId))
            ->groupBy('vh.user_id','u.name','u.email')
            ->orderByDesc(DB::raw('SUM(vh.hours)'))
            ->select([
                'vh.user_id',
                'u.name',
                'u.email',
                DB::raw('COUNT(*) as sessions'),
                DB::raw('ROUND(SUM(vh.hours),2) as total_hours'),
                DB::raw('MIN(vh.created_at) as first_at'),
                DB::raw('MAX(vh.created_at) as last_at'),
            ])
            ->get();

        // Recent sessions
        $sessions = DB::table('volunteer_hours as vh')
            ->leftJoin('users as u', 'u.id', '=', 'vh.user_id')
            ->leftJoin('opportunities as o', 'o.id', '=', 'vh.opportunity_id')
            ->when($scopeId, fn($q) => $q->where('vh.opportunity_id', $scopeId))
            ->orderByDesc('vh.created_at')
            ->limit(250)
            ->select([
                'vh.id','vh.user_id','vh.opportunity_id','vh.hours','vh.note','vh.created_at',
                'u.name as user_name','u.email as user_email',
                DB::raw('COALESCE(o.title,o.name) as opp_name')
            ])->get();

        $title = $scopeId
            ? ('Attendance • Opportunity #'.$scopeId.($opportunity?(' • '.( $opportunity->title ?? $opportunity->name ?? '')):''))
            : 'Attendance • All Opportunities';

        return view('admin.hours.show', compact('title','opportunity','totals','sessions','scopeId'));
    }

    // convenience URL /admin/hours for a site-wide rollup
    public function showAll(Request $r)
    {
        return $this->show($r, 'all');
    }
}

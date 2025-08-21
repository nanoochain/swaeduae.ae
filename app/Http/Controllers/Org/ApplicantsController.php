<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApplicantsController extends Controller
{
    protected function detect(): array
    {
        if (Schema::hasTable('event_applications')) {
            $cols = DB::getSchemaBuilder()->getColumnListing('event_applications');
            return [
                'table' => 'event_applications',
                'cols'  => [
                    'id'         => in_array('id',$cols)?'id':null,
                    'event_id'   => in_array('event_id',$cols)?'event_id':null,
                    'user_id'    => in_array('user_id',$cols)?'user_id':null,
                    'status'     => in_array('status',$cols)?'status':(in_array('approved',$cols)?'approved':null),
                    'applied_at' => in_array('applied_at',$cols)?'applied_at':(in_array('created_at',$cols)?'created_at':null),
                    'approved_at'=> in_array('approved_at',$cols)?'approved_at':null,
                    'rejected_at'=> in_array('rejected_at',$cols)?'rejected_at':null,
                ],
            ];
        }
        if (Schema::hasTable('event_registrations')) {
            $cols = DB::getSchemaBuilder()->getColumnListing('event_registrations');
            return [
                'table' => 'event_registrations',
                'cols'  => [
                    'id'         => in_array('id',$cols)?'id':null,
                    'event_id'   => in_array('event_id',$cols)?'event_id':null,
                    'user_id'    => in_array('user_id',$cols)?'user_id':null,
                    'status'     => in_array('status',$cols)?'status':(in_array('approved',$cols)?'approved':null),
                    'applied_at' => in_array('created_at',$cols)?'created_at':null,
                    'approved_at'=> in_array('approved_at',$cols)?'approved_at':null,
                    'rejected_at'=> in_array('rejected_at',$cols)?'rejected_at':null,
                ],
            ];
        }
        return ['table'=>null,'cols'=>[]];
    }

    protected function assertEventOwned(int $eventId): object
    {
        $u = Auth::user();
        $q = DB::table('events')->where('id',$eventId);
        if (!($u->is_admin ?? 0)) {
            $org = DB::table('organizations')
                ->where(function($w) use ($u) {
                    $w->orWhere('owner_user_id',$u->id)
                      ->orWhere('user_id',$u->id)
                      ->orWhere('owner_id',$u->id);
                })->first();
            abort_unless($org, 403);
            $q->where('organization_id',$org->id);
        }
        $event = $q->first();
        abort_unless($event, 404);
        return $event;
    }

    public function index(Request $request, int $eventId)
    {
        $event = $this->assertEventOwned($eventId);
        $det = $this->detect();

        $rows = collect(); $schemaInfo = null;
        if ($det['table']) {
            $t = $det['table']; $c = $det['cols'];
            $rows = DB::table($t.' as a')
                ->join('users as u', 'u.id', '=', 'a.'.$c['user_id'])
                ->selectRaw('a.'.($c['id']??'id').' as id, u.name as user_name, u.email as user_email, a.'.($c['status']??'status').' as status, a.'.($c['applied_at']??'created_at').' as applied_at')
                ->where('a.'.$c['event_id'], $eventId)
                ->orderByDesc('a.'.($c['applied_at']??'id'))
                ->paginate(20);

            if ((Auth::user()->is_admin ?? 0) && ($c['status'] ?? null)) {
                $meta = DB::selectOne("
                    SELECT DATA_TYPE data_type, COLUMN_TYPE column_type
                    FROM information_schema.COLUMNS
                    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?
                    LIMIT 1
                ", [$t, $c['status']]);
                $allowed = [];
                if ($meta && strtolower($meta->data_type) === 'enum') {
                    preg_match_all("/'([^']+)'/", $meta->column_type ?? '', $m);
                    $allowed = $m[1] ?? [];
                }
                $schemaInfo = [
                    'table'=>$t,'column'=>$c['status'],
                    'data_type'=>$meta->data_type ?? null,
                    'column_type'=>$meta->column_type ?? null,
                    'allowed'=>$allowed,
                ];
            }
        }

        return view('org.opportunities.applicants', compact('event','rows','schemaInfo'));
    }

    protected function statusValue(string $table, string $col, string $action)
    {
        $target = $action === 'approve' ? 'approved' : 'rejected';
        $meta = DB::selectOne("
            SELECT DATA_TYPE data_type, COLUMN_TYPE column_type
            FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?
            LIMIT 1
        ", [$table, $col]);

        if (!$meta) return $target;
        $type = strtolower($meta->data_type ?? '');
        $ctype = strtolower($meta->column_type ?? '');

        if ($type === 'enum' && $ctype) {
            preg_match_all("/'([^']+)'/", $ctype, $m);
            $allowed = $m[1] ?? [];
            $mapApproved = ['approved','accept','accepted','confirm','confirmed','yes','ok'];
            $mapRejected = ['rejected','declined','denied','refused','no','cancelled','canceled'];
            if ($target === 'approved') { foreach ($mapApproved as $w) if (in_array($w,$allowed,true)) return $w; }
            else { foreach ($mapRejected as $w) if (in_array($w,$allowed,true)) return $w; }
            return $allowed[0] ?? $target;
        }
        if (in_array($type, ['tinyint','smallint','int','integer','bigint','mediumint','bit'], true)) {
            return $target === 'approved' ? 1 : 0;
        }
        return $target;
    }

    public function decision(Request $request, int $eventId, int $appId)
    {
        $action = Str::lower($request->input('action'));
        abort_unless(in_array($action, ['approve','reject']), 422);

        $this->assertEventOwned($eventId);
        $det = $this->detect();
        abort_unless($det['table'], 500);

        $t = $det['table']; $c = $det['cols']; $now = now();

        $set = [];
        if ($c['status']) { $set[$c['status']] = $this->statusValue($t, $c['status'], $action); }
        if ($action==='approve' && $c['approved_at']) $set[$c['approved_at']]=$now;
        if ($action==='reject'  && $c['rejected_at']) $set[$c['rejected_at']]=$now;

        DB::table($t)->where($c['id'] ?? 'id', $appId)->where($c['event_id'] ?? 'event_id', $eventId)->update($set);
        return back()->with('status', $action==='approve' ? __('swaed.approved') ?? 'Approved' : __('swaed.rejected') ?? 'Rejected');
    }

    public function exportCsv(Request $request, int $eventId)
    {
        $event = $this->assertEventOwned($eventId);
        $det = $this->detect();
        abort_unless($det['table'], 404);

        $t = $det['table']; $c = $det['cols'];
        $rows = DB::table($t.' as a')
            ->join('users as u','u.id','=','a.'.$c['user_id'])
            ->selectRaw('a.'.($c['id']??'id').' as id, u.name as user_name, u.email as user_email, a.'.($c['status']??'status').' as status, a.'.($c['applied_at']??'created_at').' as applied_at')
            ->where('a.'.$c['event_id'], $eventId)
            ->orderBy('a.'.($c['id']??'id'))
            ->cursor();

        $cb = function() use ($rows,$eventId){
            $out = fopen('php://output','w');
            fputcsv($out, ['event_id','application_id','name','email','status','applied_at']);
            foreach ($rows as $r) {
                fputcsv($out, [$eventId, $r->id, $r->user_name, $r->user_email, $r->status, $r->applied_at]);
            }
            fclose($out);
        };
        $filename = "event_{$eventId}_applicants.csv";
        return new StreamedResponse($cb, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }
}

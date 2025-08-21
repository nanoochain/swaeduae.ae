<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class CertificateController extends Controller
{
    private function guardAdmin(Request $request)
    {
        $u = $request->user();
        if (!($u && ($u->can('admin') || ($u->is_admin ?? false) || $u->hasRole('admin')))) abort(403);
    }

    private function checksum(array $payload): string
    {
        $secret = config('app.key') ?? 'swaeduae';
        return hash_hmac('sha256', implode('|', $payload), $secret);
    }

    public function index(Request $request)
    {
        $this->guardAdmin($request);

        if (!Schema::hasTable('certificates')) {
            return view('admin.certificates.index', ['rows'=>collect(), 'filters'=>[], 'message'=>'No certificates table.']);
        }

        $filters = [
            'q' => $request->get('q'),
            'status' => $request->get('status') // valid / revoked
        ];

        $q = DB::table('certificates as c')
            ->select('c.*','u.name as user_name','u.email','o.title as opportunity_title')
            ->leftJoin('users as u','u.id','=','c.user_id')
            ->leftJoin('opportunities as o','o.id','=','c.opportunity_id')
            ->orderBy('c.created_at','desc');

        if ($filters['q']) {
            $like = '%'.$filters['q'].'%';
            $q->where(function($w) use ($like){
                $w->where('c.code','like',$like)
                  ->orWhere('u.name','like',$like)
                  ->orWhere('u.email','like',$like)
                  ->orWhere('o.title','like',$like);
            });
        }
        if ($filters['status'] === 'valid')   $q->whereNull('c.revoked_at');
        if ($filters['status'] === 'revoked') $q->whereNotNull('c.revoked_at');

        $rows = $q->paginate(20)->appends($request->query());

        return view('admin.certificates.index', compact('rows','filters'));
    }

    public function generateForOpportunity(Request $request, $id)
    {
        $this->guardAdmin($request);

        if (!Schema::hasTable('volunteer_hours')) return back()->with('error','volunteer_hours missing.');
        if (!Schema::hasTable('certificates'))    return back()->with('error','certificates missing.');

        $op = DB::table('opportunities')->where('id',$id)->first();
        if (!$op) return back()->with('error','Opportunity not found.');

        $hours = DB::table('volunteer_hours')->where('opportunity_id',$id)->where('minutes','>',0)->get();
        $count = 0;

        foreach ($hours as $h) {
            $user = DB::table('users')->where('id',$h->user_id)->first();
            if (!$user) continue;

            $exists = DB::table('certificates')
                ->where('user_id',$user->id)
                ->where('opportunity_id',$id)
                ->whereNull('revoked_at')
                ->first();
            if ($exists) continue;

            $title = 'Volunteer Certificate';
            $code  = 'SU-'.strtoupper(bin2hex(random_bytes(3))).'-'.date('ymd');
            $verifyUrl = url('/verify/'.$code);

            // SVG QR
            $qrSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(220)->generate($verifyUrl);

            $payload = [$code, $user->name ?? '', $op->title ?? '', (string) ($h->minutes ?? 0), date('Y-m-d')];
            $checksum = $this->checksum($payload);

            $relPath = 'certificates/'.$code.'.pdf';
            $pdf = PDF::loadView('certificates.template', [
                'code'=>$code,'user'=>$user,'op'=>$op,'minutes'=>$h->minutes ?? 0,'qrSvg'=>$qrSvg,'issued_at'=>now(),
            ])->setPaper('a4');
            Storage::disk('public')->put($relPath, $pdf->output());

            // Optional columns (set only if they exist)
            $extras = [];
            if (Schema::hasColumn('certificates','certificate_number')) $extras['certificate_number'] = $code;
            if (Schema::hasColumn('certificates','verification_code'))  $extras['verification_code']  = $code;
            if (Schema::hasColumn('certificates','issued_at'))          $extras['issued_at']          = now();
            if (Schema::hasColumn('certificates','issued_date'))        $extras['issued_date']        = now()->toDateString();
            if (Schema::hasColumn('certificates','status'))             $extras['status']             = 'valid';
            if (Schema::hasColumn('certificates','language'))           $extras['language']           = app()->getLocale() ?? 'en';
            if (Schema::hasColumn('certificates','issuer'))             $extras['issuer']             = 'SawaedUAE';

            DB::table('certificates')->insert(array_merge([
                'user_id'        => $user->id,
                'opportunity_id' => $id,
                'title'          => $title,
                'code'           => $code,
                'file_path'      => 'storage/'.$relPath,
                'checksum'       => $checksum,
                'created_at'     => now(),
                'updated_at'     => now(),
            ], $extras));

            DB::table('certificate_deliveries')->insert([
                'certificate_id' => DB::getPdo()->lastInsertId(),
                'channel'        => 'email',
                'status'         => 'queued',
                'meta'           => $user->email ?? '',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            $count++;
        }

        if (auth()->check()) {            auth()->user()->notify(new AppNotificationsSimpleMessage([                'title' => 'Certificates generated',                'body'  => 'Batch issue completed successfully.',            ]));        }
        return back()->with('success', "Generated $count certificates.");
    }

    public function show(Request $request, $id)
    {
        $this->guardAdmin($request);
        $c = DB::table('certificates as c')
            ->select('c.*','u.name as user_name','u.email','o.title as opportunity_title')
            ->leftJoin('users as u','u.id','=','c.user_id')
            ->leftJoin('opportunities as o','o.id','=','c.opportunity_id')
            ->where('c.id',$id)->first();
        abort_unless($c, 404);
        return view('admin.certificates.show', compact('c'));
    }

    public function resendEmail(Request $request, $id)
    {
        $this->guardAdmin($request);
        $c = DB::table('certificates')->where('id',$id)->first();
        if (!$c) return back()->with('error','Certificate not found.');
        $user = DB::table('users')->where('id',$c->user_id)->first();

        $sent = false; $resp = '';
        try {
            \Illuminate\Support\Facades\Mail::send('emails.certificate', ['c'=>$c,'user'=>$user], function($m) use ($c, $user) {
                $m->to($user->email ?? '')->subject('Your Volunteer Certificate');
                if (!empty($c->file_path) && file_exists(public_path($c->file_path))) {
                    $m->attach(public_path($c->file_path));
                }
            });
            $sent = true; $resp = 'sent';
        } catch (\Throwable $e) {
            $resp = substr($e->getMessage(),0,190);
        }

        DB::table('certificate_deliveries')->insert([
            'certificate_id'=>$id,
            'channel'=>'email',
            'status'=>$sent ? 'sent':'failed',
            'meta'=>$user->email ?? '',
            'response_excerpt'=>$resp,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        return back()->with($sent ? 'success' : 'error', $sent ? 'Email sent.' : ('Email failed: '.$resp));
    }

    public function sendWhatsApp(Request $request, $id)
    {
        $this->guardAdmin($request);
        $c = DB::table('certificates')->where('id',$id)->first();
        if (!$c) return back()->with('error','Certificate not found.');
        $link = url('/'.$c->file_path);
        $wa   = 'https://wa.me/?text='.rawurlencode('Your volunteer certificate: '.$link.' (code: '.$c->code.')');

        DB::table('certificate_deliveries')->insert([
            'certificate_id'=>$id,
            'channel'=>'whatsapp',
            'status'=>'queued',
            'meta'=>$wa,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        $user->notify(new AppNotificationsCertificateIssued([

            'title' => 'Certificate issued',

            'body'  => 'Your volunteering certificate is now available.',

        ]));
        $user->notify(new AppNotificationsCertificateIssued([

            'title' => 'Certificate issued',

            'body'  => 'Your volunteering certificate is now available.',

        ]));
        return redirect($wa);
    }

    public function revoke(Request $request, $id)
    {
        $this->guardAdmin($request);
        DB::table('certificates')->where('id',$id)->update(['revoked_at'=>now(),'updated_at'=>now()]);
        return back()->with('success','Certificate revoked.');
    }

    public function reissue(Request $request, $id)
    {
        $this->guardAdmin($request);
        $old = DB::table('certificates')->where('id',$id)->first();
        if (!$old) return back()->with('error','Certificate not found.');

        $op = $old->opportunity_id ? DB::table('opportunities')->where('id',$old->opportunity_id)->first() : null;
        $user = DB::table('users')->where('id',$old->user_id)->first();

        $code  = 'SU-'.strtoupper(bin2hex(random_bytes(3))).'-'.date('ymd');
        $verifyUrl = url('/verify/'.$code);

        $qrSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(220)->generate($verifyUrl);
        $payload = [$code, $user->name ?? '', $op->title ?? ($old->title ?? ''), '0', date('Y-m-d')];
        $checksum = $this->checksum($payload);

        $relPath = 'certificates/'.$code.'.pdf';
        $pdf = PDF::loadView('certificates.template', [
            'code'=>$code,'user'=>$user,'op'=>$op,'minutes'=>0,'qrSvg'=>$qrSvg,'issued_at'=>now(),
        ])->setPaper('a4');
        Storage::disk('public')->put($relPath, $pdf->output());

        DB::table('certificates')->where('id',$id)->update(['revoked_at'=>now(),'updated_at'=>now()]);

        $extras = [];
        if (Schema::hasColumn('certificates','certificate_number')) $extras['certificate_number'] = $code;
        if (Schema::hasColumn('certificates','verification_code'))  $extras['verification_code']  = $code;
        if (Schema::hasColumn('certificates','issued_at'))          $extras['issued_at']          = now();
        if (Schema::hasColumn('certificates','issued_date'))        $extras['issued_date']        = now()->toDateString();
        if (Schema::hasColumn('certificates','status'))             $extras['status']             = 'valid';
        if (Schema::hasColumn('certificates','language'))           $extras['language']           = app()->getLocale() ?? 'en';
        if (Schema::hasColumn('certificates','issuer'))             $extras['issuer']             = 'SawaedUAE';

        DB::table('certificates')->insert(array_merge([
            'user_id'=>$old->user_id,
            'opportunity_id'=>$old->opportunity_id,
            'title'=>$old->title ?? 'Volunteer Certificate',
            'code'=>$code,
            'file_path'=>'storage/'.$relPath,
            'checksum'=>$checksum,
            'created_at'=>now(),
            'updated_at'=>now(),
        ], $extras));

        return back()->with('success','Reissued with new code.');
    }
}

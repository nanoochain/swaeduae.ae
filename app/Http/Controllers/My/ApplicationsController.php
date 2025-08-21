<?php

namespace App\Http\Controllers\My;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ApplicationsController extends Controller
{
    public function index(Request $request)
    {
        $u = $request->user();
        if (!Schema::hasTable('applications')) { return view('my/applications', ['rows'=>collect()]); }
        $rows = DB::table('applications as a')
            ->leftJoin('opportunities as o','o.id','=','a.opportunity_id')
            ->select('a.*','o.title as opportunity_title')
            ->where('a.user_id',$u->id)->orderByDesc('a.id')->paginate(20);
        return view('my/applications', compact('rows'));
    }
}

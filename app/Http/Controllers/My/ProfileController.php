<?php

namespace App\Http\Controllers\My;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return view('my/profile', ['u'=>$request->user()]);
    }

    public function update(Request $request)
    {
        $u = $request->user();
        $data = $request->validate([
            'name' => 'required|string|max:190',
        ]);
        if (Schema::hasTable('users') && Schema::hasColumn('users','name')) {
            DB::table('users')->where('id',$u->id)->update(['name'=>$data['name'], 'updated_at'=>now()]);
        }
        return redirect()->route('vol.profile')->with('success', __('Profile updated.'));
    }
}

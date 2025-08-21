<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();
        $profile = DB::table('profiles')->where('user_id', $user->id)->first();
        return view('profile.edit', compact('user','profile'));
    }

    public function update(Request $request)
    {
        $user = $request->user();

        // Age 14+ rule if DOB provided
        $validated = $request->validate([
            'first_name' => 'nullable|string|max:120',
            'last_name'  => 'nullable|string|max:120',
            'nationality'=> 'nullable|string|max:80',
            'emirate'    => 'nullable|string|max:80',
            'gender'     => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before_or_equal:' . now()->subYears(14)->format('Y-m-d'),
            'phone'      => 'nullable|string|max:32',
            'emirates_id'=> 'nullable|string|max:32',
            'photo'      => 'nullable|image|max:4096',
            'skills'     => 'nullable|array',
            'interests'  => 'nullable|array',
            'availability'=> 'nullable|array',
            'address'    => 'nullable|string|max:2000',
        ]);

        // update user basic fields
        DB::table('users')->where('id',$user->id)->update([
            'phone' => $request->phone,
            'emirates_id' => $request->emirates_id,
            'updated_at' => now(),
        ]);

        // handle photo
        $photo = null;
        if ($request->hasFile('photo')) {
            @mkdir(public_path('uploads/profiles'), 0775, true);
            $photo = 'uploads/profiles/'.Str::random(8).'_'.time().'.'.$request->file('photo')->getClientOriginalExtension();
            $request->file('photo')->move(public_path('uploads/profiles'), basename($photo));
        }

        $payload = [
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'nationality'=>$request->nationality,
            'emirate'=>$request->emirate,
            'gender'=>$request->gender,
            'date_of_birth'=>$request->date_of_birth,
            'address'=>$request->address,
            'skills'=> $request->filled('skills') ? json_encode($request->skills) : null,
            'interests'=> $request->filled('interests') ? json_encode($request->interests) : null,
            'availability'=> $request->filled('availability') ? json_encode($request->availability) : null,
            'updated_at'=>now(),
        ];
        if ($photo) $payload['photo'] = $photo;

        $exists = DB::table('profiles')->where('user_id',$user->id)->exists();
        if ($exists) {
            DB::table('profiles')->where('user_id',$user->id)->update($payload);
        } else {
            $payload['user_id'] = $user->id;
            $payload['created_at'] = now();
            DB::table('profiles')->insert($payload);
        }

        return redirect()->route('profile.edit')->with('status', __('Profile updated'));
    }
}

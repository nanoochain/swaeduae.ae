<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $fields = [
            'site.name'          => Setting::get('site.name', 'SawaedUAE'),
            'site.logo'          => Setting::get('site.logo'),
            'site.main_photo'    => Setting::get('site.main_photo'),
            'site.support_email' => Setting::get('site.support_email', 'support@swaeduae.ae'),
            'google.client_id'   => Setting::get('google.client_id'),
            'google.client_secret'=> Setting::get('google.client_secret'),
            'apple.client_id'    => Setting::get('apple.client_id'),
            'apple.key'          => Setting::get('apple.key'),
            'payment.provider'   => Setting::get('payment.provider'),
            'payment.public_key' => Setting::get('payment.public_key'),
            'payment.secret_key' => Setting::get('payment.secret_key'),
        ];
        return view('admin.settings.index', compact('fields'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'nullable|string|max:120',
            'support_email' => 'nullable|email',
            'logo' => 'nullable|image|max:2048',
            'main_photo' => 'nullable|image|max:4096',
        ]);

        if ($request->filled('site_name')) Setting::set('site.name', $request->site_name);
        if ($request->filled('support_email')) Setting::set('site.support_email', $request->support_email);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('public/site');
            Setting::set('site.logo', Storage::url($path));
        }
        if ($request->hasFile('main_photo')) {
            $path = $request->file('main_photo')->store('public/site');
            Setting::set('site.main_photo', Storage::url($path));
        }

        // Credentials (mark as secret)
        foreach ([
            'google.client_id' => 'google_client_id',
            'google.client_secret' => 'google_client_secret',
            'apple.client_id' => 'apple_client_id',
            'apple.key' => 'apple_key',
            'payment.provider' => 'payment_provider',
            'payment.public_key' => 'payment_public_key',
            'payment.secret_key' => 'payment_secret_key',
        ] as $key => $formField) {
            if ($request->filled($formField)) {
                Setting::set($key, $request->input($formField), true);
            }
        }

        return back()->with('ok', __('Settings saved.'));
    }
}

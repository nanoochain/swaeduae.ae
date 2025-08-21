<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UaepassController extends Controller
{
    public function redirect(Request $request)
    {
        $client = config('services.uaepass.client_id');
        $secret = config('services.uaepass.client_secret');
        $callback = config('services.uaepass.redirect_uri');

        if (!$client || !$secret || !$callback) {
            return back()->with('error', __('swaed.missing_keys'))->withInput();
        }

        // Placeholder auth URL (replace with real UAE PASS endpoint when ready)
        $authUrl = $callback . '?mock_uaepass=1';
        return redirect()->away($authUrl);
    }

    public function callback(Request $request)
    {
        // In real flow, validate state/code and map user. For now, just report status.
        return redirect()->route('admin.settings.index')->with('status', __('swaed.ok_configured'));
    }
}

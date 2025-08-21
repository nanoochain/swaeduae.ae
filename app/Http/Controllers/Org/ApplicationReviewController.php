<?php

namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\RedirectResponse;

class ApplicationReviewController extends Controller
{
    public function shortlist(Application $application): RedirectResponse
    {
        $application->status = 'shortlisted';
        $application->save();

        return back()->with('status', 'Shortlisted');
    }

    public function accept(Application $application): RedirectResponse
    {
        $application->status = 'accepted';
        $application->save();

        return back()->with('status', 'Accepted');
    }

    public function reject(Application $application): RedirectResponse
    {
        $application->status = 'rejected';
        $application->save();

        return back()->with('status', 'Rejected');
    }
}

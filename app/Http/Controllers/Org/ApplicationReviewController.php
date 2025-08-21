<?php
namespace App\Http\Controllers\Org;

use App\Http\Controllers\Controller;
use App\Models\Application;

class ApplicationReviewController extends Controller
{
    public function shortlist(Application ){ (["status"=>"shortlisted"]); return back()->with("status","Shortlisted"); }
    public function accept(Application ){ (["status"=>"accepted"]); return back()->with("status","Accepted"); }
    public function reject(Application ){ (["status"=>"rejected"]); return back()->with("status","Rejected"); }
}
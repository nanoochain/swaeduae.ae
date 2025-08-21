<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use App\Models\Category;
use App\Models\Partner;
use App\Models\Story;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home()
    {
        $featured = Opportunity::where('featured', true)->latest()->take(6)->get();
        $categories = Category::all();
        return view('public.home', compact('featured','categories'));
    }

    public function about() { return view('public.about'); }

    public function contact() { return view('public.contact'); }

    public function sendContact(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'message' => 'required|string',
        ]);
        // TODO: send email with $data
        return back()->with('status','Thank you! We\'ll get back to you soon.');
    }

    public function faq() { return view('public.faq'); }

    public function partners()
    {
        $partners = Partner::all();
        return view('public.partners', compact('partners'));
    }

    public function stories()
    {
        $stories = Story::latest()->paginate(9);
        return view('public.stories', compact('stories'));
    }

    public function showStory(Story $story)
    {
        return view('public.story_show', compact('story'));
    }
}

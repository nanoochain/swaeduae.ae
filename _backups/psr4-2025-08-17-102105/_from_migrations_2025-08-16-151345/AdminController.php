<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Event;
use App\Models\Certificate;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class AdminController extends Controller
{
    public function dashboard()
    {
        $users = User::count();
        $events = Event::count();
        $certs = Certificate::count();
        $logs = File::exists(storage_path('logs/laravel.log')) ? implode("\n", array_slice(explode("\n", File::get(storage_path('logs/laravel.log'))), -20)) : 'No logs found.';
        return view('admin.dashboard', compact('users', 'events', 'certs', 'logs'));
    }

    public function listUsers()
    {
        $users = User::paginate(20);
        return view('admin.users', compact('users'));
    }

    public function listEvents()
    {
        $events = Event::paginate(20);
        return view('admin.events', compact('events'));
    }

    public function listCertificates()
    {
        $certs = Certificate::paginate(20);
        return view('admin.certificates', compact('certs'));
    }

    // Simple user activation toggle
    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        $user->active = !$user->active;
        $user->save();
        return back()->with('success', 'User status updated.');
    }

    // Manual backup stub
    public function backup(Request $request)
    {
        \Log::info('Admin triggered manual backup at '.now());
        return back()->with('success', 'Backup triggered (stub). Use hosting backup tools for full backups.');
    }
}

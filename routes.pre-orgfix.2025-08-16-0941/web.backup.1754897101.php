<?php
use App\Http\Controllers\OpportunityController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrganizationOpportunityController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Define the public and dashboard routes for SawaedUAE.
| These routes are grouped by role to protect dashboard pages.
*/

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');

/* Public-facing opportunities */
Route::get('/opportunities/{opportunity}', [OpportunityController::class, 'show'])->name('opportunities.show');
Route::post('/opportunities/{opportunity}/apply', [OpportunityController::class, 'apply'])
    ->middleware(['auth','role:volunteer'])
    ->name('opportunities.apply');

/* Categories & events */
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

/* Partners & stories */
Route::get('/partners', [PartnerController::class, 'index'])->name('partners.index');
Route::get('/stories', [StoryController::class, 'index'])->name('stories.index');
Route::get('/stories/{story}', [StoryController::class, 'show'])->name('stories.show');

/* Leaderboard */
Route::get('/leaderboard', [VolunteerController::class, 'leaderboard'])->name('leaderboard');

/* Volunteer dashboard & profile */
Route::middleware(['auth','role:volunteer'])->group(function () {
    Route::get('/dashboard', [VolunteerController::class, 'dashboard'])->name('volunteer.dashboard');
    Route::get('/profile', [VolunteerController::class, 'edit'])->name('volunteer.profile.edit');
    Route::post('/profile', [VolunteerController::class, 'update'])->name('volunteer.profile.update');
});

/* Organization dashboard & resources */
Route::middleware(['auth','org:organization'])->group(function () {
    Route::get('/organization/dashboard', [OrganizationController::class, 'dashboard'])->name('organization.dashboard');
    Route::resource('organization/opportunities', OrganizationOpportunityController::class);
});

/* Team dashboard */
Route::middleware(['auth','role:team'])->group(function () {
    Route::get('/team/dashboard', [TeamController::class, 'dashboard'])->name('team.dashboard');
});

/* Admin dashboard */
Route::middleware(['auth','role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});

/* Organization self-service registration + admin approval */
use App\Http\Controllers\OrgRegistrationController;

Route::get('/organizations/register', [OrgRegistrationController::class, 'create'])
    ->name('organizations.register');
Route::post('/organizations/register', [OrgRegistrationController::class, 'store'])
    ->name('organizations.register.store');

/* Admin: view & approve pending orgs */
Route::middleware(['auth','role:admin'])->group(function () {
    Route::get('/admin/organizations/pending', [OrgRegistrationController::class, 'pending'])
        ->name('admin.organizations.pending');
    Route::post('/admin/organizations/{organization}/approve', [OrgRegistrationController::class, 'approve'])
        ->name('admin.organizations.approve');
    Route::post('/admin/organizations/{organization}/reject', [OrgRegistrationController::class, 'reject'])
        ->name('admin.organizations.reject');
});

// ----- Opportunities public pages -----

Route::get('/opportunities/{opportunity}', [OpportunityController::class, 'show'])->name('opportunities.show');

// (Optional) simple apply stub – redirect guests to login, otherwise flash success
Route::post('/opportunities/{opportunity}/apply', [OpportunityController::class, 'apply'])
    ->middleware('auth')
    ->name('opportunities.apply');

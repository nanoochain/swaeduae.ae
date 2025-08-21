<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Opportunity;
use Illuminate\Support\Facades\DB;

class ViewServiceProvider extends ServiceProvider {
  public function register(): void {}
  public function boot(): void {
    View::composer('partials.nav', function($view){
      $view->with('navLatestOpportunities',
        Opportunity::orderByDesc('id')->limit(5)->get(['id','title'])
      );
    });
    View::composer('partials.hero-home', function($view){
      $view->with('hero', DB::table('settings')->where('key','home.hero')->value('value'));
    });
  }
}

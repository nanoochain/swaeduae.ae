<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;

class BuildSitemaps extends Command
{
    protected $signature = 'swaed:build-sitemaps';
    protected $description = 'Generate localized sitemap index + section sitemaps';

    public function handle(): int
    {
        $base = rtrim(config('app.url', 'https://swaeduae.ae'), '/');
        $publicPath = public_path('sitemaps');
        if (!is_dir($publicPath)) { @mkdir($publicPath, 0775, true); }

        $locales = ['ar','en'];
        $now = Carbon::now();

        $built = [];
        foreach ($locales as $loc) {
            $smap = Sitemap::create();

            // Home + list pages
            $smap->add(
                Url::create("{$base}/?lang={$loc}")
                   ->setLastModificationDate($now)
            );
            $smap->add(
                Url::create("{$base}/opportunities?lang={$loc}")
                   ->setLastModificationDate($now)
            );

            // Opportunities
            if (Schema::hasTable('opportunities')) {
                $rows = DB::table('opportunities')->select('id','title','updated_at')->orderByDesc('updated_at')->limit(10000)->get();
                foreach ($rows as $r) {
                    $slug = Str::slug($r->title ?? "opportunity-{$r->id}");
                    $url  = "{$base}/opportunities/{$r->id}-{$slug}?lang={$loc}";
                    $last = $r->updated_at ? Carbon::parse($r->updated_at) : $now;
                    $smap->add(
                        Url::create($url)
                           ->setLastModificationDate($last)
                           ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                           ->setPriority(0.8)
                    );
                }
            }

            // Events
            if (Schema::hasTable('events')) {
                $rows = DB::table('events')->select('id','title','updated_at')->orderByDesc('updated_at')->limit(10000)->get();
                foreach ($rows as $r) {
                    $slug = Str::slug($r->title ?? "event-{$r->id}");
                    $url  = "{$base}/events/{$r->id}-{$slug}?lang={$loc}";
                    $last = $r->updated_at ? Carbon::parse($r->updated_at) : $now;
                    $smap->add(
                        Url::create($url)
                           ->setLastModificationDate($last)
                           ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                           ->setPriority(0.6)
                    );
                }
            }

            // Organizations
            if (Schema::hasTable('organizations')) {
                $rows = DB::table('organizations')->select('id','name','updated_at')->orderByDesc('updated_at')->limit(10000)->get();
                foreach ($rows as $r) {
                    $slug = Str::slug($r->name ?? "org-{$r->id}");
                    $url  = "{$base}/organizations/{$r->id}-{$slug}?lang={$loc}";
                    $last = $r->updated_at ? Carbon::parse($r->updated_at) : $now;
                    $smap->add(
                        Url::create($url)
                           ->setLastModificationDate($last)
                           ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                           ->setPriority(0.5)
                    );
                }
            }

            // Save per-locale sitemap
            $file = public_path("sitemaps/sitemap-{$loc}.xml");
            $smap->writeToFile($file);
            $built[] = ['loc' => "{$base}/sitemaps/sitemap-{$loc}.xml", 'lastmod' => $now];
        }

        // Index
        $index = SitemapIndex::create();
        foreach ($built as $b) { $index->add($b['loc'], $b['lastmod']); }
        $indexFile = public_path('sitemaps/sitemap-index.xml');
        $index->writeToFile($indexFile);
        copy($indexFile, public_path('sitemap.xml'));

        $this->info("Sitemaps built.");
        return self::SUCCESS;
    }
}

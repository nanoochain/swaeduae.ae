<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        \$regions = [
            'Abu Dhabi',
            'Dubai',
            'Sharjah',
            'Ajman',
            'Fujairah',
            'Ras Al Khaimah',
            'Umm Al Quwain'
        ];

        foreach (\$regions as \$region) {
            DB::table('regions')->insertOrIgnore(['name' => \$region]);
        }
    }
}

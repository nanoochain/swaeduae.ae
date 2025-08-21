<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('opportunities', function (Blueprint $t) {
            if (!Schema::hasColumn('opportunities','geofence_lat')) {
                $t->decimal('geofence_lat',10,7)->nullable()->after('location');
                $t->decimal('geofence_lng',10,7)->nullable()->after('geofence_lat');
                $t->integer('geofence_radius_m')->nullable()->after('geofence_lng');
            }
        });
    }

    public function down(): void
    {
        Schema::table('opportunities', function (Blueprint $t) {
            foreach (['geofence_lat','geofence_lng','geofence_radius_m'] as $col) {
                if (Schema::hasColumn('opportunities',$col)) $t->dropColumn($col);
            }
        });
    }
};

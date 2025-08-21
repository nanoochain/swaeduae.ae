<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $t) {
            if (!Schema::hasColumn('attendances','check_in_lat')) {
                $t->decimal('check_in_lat',10,7)->nullable()->after('check_in_at');
                $t->decimal('check_in_lng',10,7)->nullable()->after('check_in_lat');
                $t->decimal('check_in_acc',8,2)->nullable()->after('check_in_lng');
                $t->decimal('check_out_lat',10,7)->nullable()->after('check_out_at');
                $t->decimal('check_out_lng',10,7)->nullable()->after('check_out_lat');
                $t->decimal('check_out_acc',8,2)->nullable()->after('check_out_lng');
            }
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $t) {
            foreach (['check_in_lat','check_in_lng','check_in_acc','check_out_lat','check_out_lng','check_out_acc'] as $col) {
                if (Schema::hasColumn('attendances',$col)) $t->dropColumn($col);
            }
        });
    }
};

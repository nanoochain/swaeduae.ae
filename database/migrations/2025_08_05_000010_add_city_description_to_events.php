<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCityDescriptionToEvents extends Migration
{
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'city')) {
                $table->string('city', 100)->nullable()->after('title');
            }
            if (!Schema::hasColumn('events', 'description')) {
                $table->text('description')->nullable()->after('city');
            }
        });
    }
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'city')) {
                $table->dropColumn('city');
            }
            if (Schema::hasColumn('events', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
}

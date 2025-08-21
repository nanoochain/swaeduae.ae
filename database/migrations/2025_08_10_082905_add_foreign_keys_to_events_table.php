<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add foreign keys if they don't exist yet
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'organization_id')) {
                $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop the foreign key constraint
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'organization_id')) {
                $table->dropForeign(['organization_id']);
            }
        });
    }
}

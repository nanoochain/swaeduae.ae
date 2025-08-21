<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // noop if table already exists
        if (! Schema::hasTable('applications')) {
            // (intentionally left blank – real create is in the primary migration)
        }
    }

    public function down(): void
    {
        // no-op
    }
};

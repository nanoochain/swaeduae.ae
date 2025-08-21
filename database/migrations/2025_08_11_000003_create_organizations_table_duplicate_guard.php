<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('organizations')) {
            // real creation handled in the primary migration
        }
    }
    public function down(): void { /* no-op */ }
};

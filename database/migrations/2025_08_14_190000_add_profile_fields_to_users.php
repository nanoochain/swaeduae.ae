<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone'))           $table->string('phone', 30)->nullable()->after('email');
            if (!Schema::hasColumn('users', 'nationality'))     $table->string('nationality', 80)->nullable();
            if (!Schema::hasColumn('users', 'gender'))          $table->string('gender', 20)->nullable();
            if (!Schema::hasColumn('users', 'dob'))             $table->date('dob')->nullable();
            if (!Schema::hasColumn('users', 'emirate'))         $table->string('emirate', 80)->nullable();
            if (!Schema::hasColumn('users', 'city'))            $table->string('city', 120)->nullable();
            if (!Schema::hasColumn('users', 'passport_no'))     $table->string('passport_no', 80)->nullable();
            if (!Schema::hasColumn('users', 'emirates_id'))     $table->string('emirates_id', 80)->nullable();

            if (!Schema::hasColumn('users', 'education'))       $table->string('education', 255)->nullable();
            if (!Schema::hasColumn('users', 'experience'))      $table->string('experience', 255)->nullable();
            if (!Schema::hasColumn('users', 'languages'))       $table->string('languages', 255)->nullable();
            if (!Schema::hasColumn('users', 'skills'))          $table->string('skills', 255)->nullable();

            if (!Schema::hasColumn('users', 'interests'))       $table->string('interests', 255)->nullable();
            if (!Schema::hasColumn('users', 'availability'))    $table->string('availability', 255)->nullable();

            if (!Schema::hasColumn('users', 'bio'))             $table->text('bio')->nullable();
            if (!Schema::hasColumn('users', 'photo_path'))      $table->string('photo_path', 255)->nullable();

            if (!Schema::hasColumn('users', 'tos_accepted_at')) $table->timestamp('tos_accepted_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach ([
                'phone','nationality','gender','dob','emirate','city','passport_no','emirates_id',
                'education','experience','languages','skills','interests','availability','bio','photo_path','tos_accepted_at'
            ] as $col) {
                if (Schema::hasColumn('users', $col)) $table->dropColumn($col);
            }
        });
    }
};

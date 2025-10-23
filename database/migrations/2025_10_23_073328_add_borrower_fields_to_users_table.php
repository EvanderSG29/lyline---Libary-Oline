<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('no_hp')->nullable()->after('active');
            $table->enum('gender', ['Male', 'Female'])->nullable()->after('no_hp');
            $table->string('class')->nullable()->after('gender');
            $table->enum('type', ['Student', 'Teacher', 'Staff', 'Guest'])->nullable()->after('class');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['no_hp', 'gender', 'class', 'type']);
        });
    }
};

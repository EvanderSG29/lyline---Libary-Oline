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
        Schema::table('databorrows', function (Blueprint $table) {
            $table->enum('type', ['User', 'Teacher', 'Staff', 'Guest'])->nullable()->after('name_borrower');
            $table->string('position')->nullable()->after('class');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('databorrows', function (Blueprint $table) {
            $table->dropColumn(['type', 'position']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->boolean('is_active')
                  ->default(true)
                  ->after('name')
                  ->comment('Whether the permission is active or inactive');
        });
        
        // Optional: Update all existing records to be active by default
        DB::table('permissions')->update(['is_active' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
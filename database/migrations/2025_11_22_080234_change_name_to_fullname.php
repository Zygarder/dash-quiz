<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //fixing compatibility issues
        DB::statement('ALTER TABLE `quiz`.`dasher` CHANGE COLUMN `name` `first_name` VARCHAR(191) NOT NULL ;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dasher', function (Blueprint $table) {
            //
        });
    }
};

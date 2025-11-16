<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->unsignedBigInteger('correct_option_id')->nullable()->after('question_text');
            $table->foreign('correct_option_id')->references('id')->on('question_options')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['correct_option_id']);
            $table->dropColumn('correct_option_id');
        });
    }

};

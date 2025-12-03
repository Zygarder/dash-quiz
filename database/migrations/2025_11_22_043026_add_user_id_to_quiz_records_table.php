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
    Schema::create('quiz_records', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
        $table->integer('score')->default(0);
        $table->date('completed_at')->nullable();
    });
}

public function down()
{
    Schema::table('quiz_records', function (Blueprint $table) {
        $table->dropColumn('completed_at');
    });
}

};

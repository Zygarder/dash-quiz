<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dasher', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('profile_photo')->nullable();
            $table->string('password');
            $table->timestamps();
        });

        Schema::create('quizzes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
        });

        Schema::create('admin', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });

        // Now create quiz_records after parents exist
        Schema::create('quiz_records', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('user_id')->constrained('dasher')->onDelete('cascade');
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->integer('score')->default(0);
            $table->timestamps();
        });

    }

    public function down(): void
    {
        // Drop dependent table first
        Schema::dropIfExists('quiz_records');
        Schema::dropIfExists('quizzes');
        // Then drop parents
        Schema::dropIfExists('dasher');
        Schema::dropIfExists('admin');
    }
};

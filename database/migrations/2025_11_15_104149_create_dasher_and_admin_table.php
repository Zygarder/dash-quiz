<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dasher', function (Blueprint $table) {
            $table->id();
            $table->string('active_status')->default(0);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('role')->default('dasher');
            $table->string('email')->unique();
            $table->string('profile_photo')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamp('last_activity')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dasher');
    }
};

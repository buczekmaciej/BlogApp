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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('displayName')->nullable();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('timezone')->default('UTC');
            $table->string('location')->nullable();
            $table->dateTime('birthDate')->nullable();
            $table->string('image')->nullable();
            $table->json('roles')->default(json_encode(['USER']));
            $table->boolean('isSubscribed')->default(false);
            $table->dateTime('lastSeen')->nullable();
            $table->string('bio')->nullable();
            $table->boolean('isDisabled')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

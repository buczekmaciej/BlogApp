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
        Schema::create('warrants', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('message');
            $table->string('status');
            $table->string('reason');
            $table->uuid('article_uuid');
            $table->uuid('author_uuid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warrants');
    }
};

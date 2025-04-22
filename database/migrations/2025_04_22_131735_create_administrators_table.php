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
        Schema::create('administrators', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Administrator::class)->constrained()->cascadeOnDelete();
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Administrator::class)->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('administrators');

        Schema::table('profiles', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\Administrator::class);
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\Administrator::class);
        });
    }
};

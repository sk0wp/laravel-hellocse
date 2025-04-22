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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->enum('status', ['active','waiting','inactive'])->default('inactive');
            $table->timestamps();
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Profile::class)->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');

        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\Profile::class);
        });
    }
};

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
        Schema::create('tally_groups', function (Blueprint $table) {
            $table->id();
            $table->string('guid')->unique();
            $table->string('name')->nullable();
            $table->string('parent')->nullable();
            $table->string('alter_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tally_groups');
    }
};

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
        Schema::create('tally_ledgers', function (Blueprint $table) {
            $table->id();
            $table->string('guid')->unique();
            $table->string('name')->nullable();
            $table->string('alias1')->nullable();
            $table->string('alias2')->nullable();
            $table->string('parent')->nullable();
            $table->string('address')->nullable();
            $table->string('alter_id')->nullable();
            $table->string('note')->nullable();
            $table->string('primary_group')->nullable();
            $table->string('previous_year_balance')->nullable();
            $table->string('this_year_balance')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('phone')->nullable();
            $table->json('xml')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tally_ledgers');
    }
};

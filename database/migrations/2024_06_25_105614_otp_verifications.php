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
        Schema::create('otp_verifications', function (Blueprint $table) {
            $table->id();
            
            $table->string('company_guid');
            $table->foreign('company_guid')->references('guid')->on('tally_companies')->onDelete('cascade');

            $table->string('ledger_guid');
            $table->foreign('ledger_guid')->references('guid')->on('tally_ledgers')->onDelete('cascade');

            $table->string('otp')->nullable();

            $table->timestamps();
        });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otp_verifications');
    }
};

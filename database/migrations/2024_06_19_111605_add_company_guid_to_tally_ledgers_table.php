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
        Schema::table('tally_ledgers', function (Blueprint $table) {
            $table->uuid('company_guid')->after('guid');
            $table->foreign('company_guid')->references('guid')->on('tally_companies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tally_ledgers', function (Blueprint $table) {

            $table->dropForeign(['company_guid']);

            $table->dropColumn('company_guid');
        });
    }
};

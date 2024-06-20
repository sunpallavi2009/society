<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            if (!Schema::hasColumn('vouchers', 'company_guid')) {
                $table->uuid('company_guid')->after('id'); // Adjust the position as needed
            }

            if (!Schema::hasColumn('vouchers', 'financial_year')) {
                $table->string('financial_year', 20)->after('company_guid'); // Adjust the length as needed
            }

            if (!Schema::hasColumn('vouchers', 'type')) {
                $table->string('type', 20)->after('financial_year'); // Adjust the length as needed
            }
        });

        // Add the unique constraint with prefix length for string columns using raw SQL
        DB::statement('ALTER TABLE vouchers ADD UNIQUE vouchers_company_guid_financial_year_type_voucher_number_unique (company_guid, financial_year(10), type(10), voucher_number)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            // Check if the unique index exists before attempting to drop it
            $schemaManager = Schema::getConnection()->getDoctrineSchemaManager();
            $indexes = $schemaManager->listTableIndexes($table->getTable());

            if (isset($indexes['vouchers_company_guid_financial_year_type_voucher_number_unique'])) {
                $table->dropUnique('vouchers_company_guid_financial_year_type_voucher_number_unique');
            }

            // Optionally, you can drop the financial_year column if it was added in this migration
            if (Schema::hasColumn('vouchers', 'financial_year')) {
                $table->dropColumn('financial_year');
            }

            // Optionally, you can drop the company_guid column if it was added in this migration
            if (Schema::hasColumn('vouchers', 'company_guid')) {
                $table->dropColumn('company_guid');
            }
        });
    }
};

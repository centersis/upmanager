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
        Schema::table('customers', function (Blueprint $table) {
            $table->string('hash')->nullable()->unique()->after('name');
        });
        
        // Generate hash for existing customers
        $customers = \App\Domains\Customer\Entities\Customer::whereNull('hash')->get();
        foreach ($customers as $customer) {
            $customer->hash = \Illuminate\Support\Str::uuid()->toString();
            $customer->save();
        }
        
        // Make hash column required after populating existing records
        Schema::table('customers', function (Blueprint $table) {
            $table->string('hash')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('hash');
        });
    }
};

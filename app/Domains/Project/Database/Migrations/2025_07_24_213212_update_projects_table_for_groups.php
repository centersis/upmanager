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
        Schema::table('projects', function (Blueprint $table) {
            // Remover a coluna group (string)
            $table->dropColumn('group');
            
            // Adicionar group_id (foreign key)
            $table->unsignedBigInteger('group_id')->nullable()->after('name');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Remover foreign key e coluna group_id
            $table->dropForeign(['group_id']);
            $table->dropColumn('group_id');
            
            // Restaurar coluna group (string)
            $table->string('group')->nullable()->after('name');
        });
    }
};

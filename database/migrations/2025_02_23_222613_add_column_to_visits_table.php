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
        Schema::table('visits', function (Blueprint $table) {
            // Optional table assigned to this visit
            $table->unsignedBigInteger('table_id')->nullable()->after('status')->comment('If a table is assigned, store it here');
            $table->foreign('table_id')->references('id')->on('tables')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->dropForeign('orders_table_id_foreign');
            $table->dropIndex('orders_table_id_foreign');
            $table->dropColumn('table_id');
            $table->dropColumn(['table_id']);
        });
    }
};
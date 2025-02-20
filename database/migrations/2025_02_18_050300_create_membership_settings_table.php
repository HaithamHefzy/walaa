<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('membership_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('platinum_visits')->comment('عدد الزيارات للعضوية البلاتينية');
            $table->integer('gold_visits')->comment('عدد الزيارات للعضوية الذهبية');
            $table->integer('silver_visits')->comment('عدد الزيارات للعضوية الفضية');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_settings');
    }
};

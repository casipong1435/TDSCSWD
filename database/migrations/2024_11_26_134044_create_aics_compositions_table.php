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
        Schema::create('aics_compositions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\AicsGis::class);
            $table->string('comp_name')->nullable();
            $table->string('comp_age')->nullable();
            $table->string('comp_relation_to_client')->nullable();
            $table->string('comp_education')->nullable();
            $table->string('comp_occupation')->nullable();
            $table->string('comp_monthly_income')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aics_compositions');
    }
};

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
        Schema::create('aics_gis', function (Blueprint $table) {
            $table->id()->from('00001');
            $table->date('date')->nullable();
            $table->string('name')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('age')->nullable();
            $table->string('sex')->nullable();
            $table->string('address')->nullable();
            $table->string('civil_status')->nullable();
            $table->string('occupation')->nullable();
            $table->string('education')->nullable();
            $table->string('monthly_income')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('relation_to_beneficiary')->nullable();
            $table->string('ik_name')->nullable();
            $table->date('ik_birthdate')->nullable();
            $table->string('ik_age')->nullable();
            $table->string('ik_sex')->nullable();
            $table->string('ik_address')->nullable();
            $table->string('ik_civil_status')->nullable();
            $table->string('ik_occupation')->nullable();
            $table->string('ik_education')->nullable();
            $table->string('ik_monthly_income')->nullable();
            $table->string('ik_mobile_number')->nullable();
            $table->string('type_of_assistance')->nullable();
            $table->string('amount_provided')->nullable();
            $table->string('social_worker_assessment')->nullable();
            $table->string('interviewee')->nullable();
            $table->string('reviewee')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aics_gis');
    }
};

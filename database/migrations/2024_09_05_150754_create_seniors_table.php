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
        Schema::create('seniors', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Benefeciary::class);
            //$table->foreign('benefeciary_id')->references('id')->on('benefeciaries')->onDelete('cascade');
            $table->string('senior_id_number')->nullable();
            $table->date('date_sic_issued')->nullable();
            $table->tinyInteger('sic_status')->nullable();
            $table->tinyInteger('socpen_benefeciary')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seniors');
    }
};

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
        Schema::create('womens', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Benefeciary::class);
            //$table->foreign('benefeciary_id')->references('id')->on('benefeciaries')->onDelete('cascade');
            $table->string('kalipi_id_number')->nullable();
            $table->date('date_kic_issued')->nullable();
            $table->tinyInteger('kic_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('womens');
    }
};

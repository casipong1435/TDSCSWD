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
        Schema::create('transition_data', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Benefeciary::class);
            $table->foreignIdFor(\App\Models\Barangay::class);
            $table->tinyInteger('from_benefeciary_type');
            $table->tinyInteger('status')->default(0);
            $table->date('date_approved')->nullable();
            $table->bigInteger('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transition_data');
    }
};

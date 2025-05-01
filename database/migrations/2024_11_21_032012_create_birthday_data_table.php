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
        Schema::create('birthday_data', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Benefeciary::class);
            $table->foreignIdFor(\App\Models\Barangay::class);
            $table->string('month')->nullable();
            $table->string('year')->nullable();
            $table->date('date_approved')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->bigInteger('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('birthday_data');
    }
};

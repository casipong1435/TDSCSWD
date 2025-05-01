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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->default(0)->nullable();
            $table->foreignIdFor(\App\Models\User::class);
            $table->date('date_checked')->nullable();
            $table->date('date_verified')->nullable();
            $table->date('date_approved')->nullable();
            $table->date('date_rejected')->nullable();
            $table->string('rejected_by')->nullable();
            $table->text('reason')->nullable();
            $table->foreignIdFor(\App\Models\Barangay::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};

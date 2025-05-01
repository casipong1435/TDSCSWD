<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Barangay;
use App\Models\MonthlyIncome;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('benefeciaries', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Barangay::class);
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(MonthlyIncome::class);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('extension_name')->nullable();
            $table->date('date_of_birth');
            $table->string('age');
            $table->string('civil_status');
            $table->string('sex');
            $table->string('educational_attainment')->nullable();
            $table->string('occupation')->nullable();
            $table->string('work_status')->nullable();
            $table->string('street')->nullable();
            //$table->foreign('barangay_id')->references('id')->on('barangays')->onDelete('cascade');
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('region')->nullable();
            $table->string('religion')->nullable();
            $table->string('contact_number')->nullable();
            $table->tinyInteger('benefeciary_type')->nullable();
            $table->tinyInteger('pantawid_benefeciary')->nullable();
            $table->tinyInteger('indigenous_person')->nullable();
            $table->tinyInteger('lgbtq')->nullable();
            $table->string('remarks')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('approved_status')->default(0);
            $table->text('reason')->nullable();
            $table->timestamps();
            $table->text('reason');
            $table->dateTime('date_rejected');
            $table->dateTime('date_approved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('benefeciaries');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasTable('reservation_services')) {
            Schema::create('reservation_services', function (Blueprint $table) {
                // Changed to unsignedBigInteger
                $table->unsignedBigInteger('RESERVATION_ID');
                $table->unsignedBigInteger('SERVICES_ID');
                
                $table->integer('Quantity')->nullable()->default(1);
                $table->timestamps();

                // Foreign key connections
                $table->foreign('RESERVATION_ID')->references('RESERVATION_ID')->on('reservation')->onDelete('cascade');
                $table->foreign('SERVICES_ID')->references('SERVICES_ID')->on('services')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_services');
    }
};
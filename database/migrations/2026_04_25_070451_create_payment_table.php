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
        if (!Schema::hasTable('payment')) {
            Schema::create('payment', function (Blueprint $table) {
                $table->id('PAYMENT_ID');
                
                $table->unsignedBigInteger('RESERVATION_ID');
                $table->unsignedBigInteger('STAFF_ID')->nullable();
                
                $table->decimal('Amount', 10, 2);
                $table->datetime('Payment_Date')->useCurrent();
                $table->string('Payment_Method', 50)->nullable();
                
                $table->timestamps();

                // Foreign key connections
                $table->foreign('RESERVATION_ID')->references('RESERVATION_ID')->on('reservation')->onDelete('cascade');
                $table->foreign('STAFF_ID')->references('STAFF_ID')->on('staff')->onDelete('set null');
            });
        }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};
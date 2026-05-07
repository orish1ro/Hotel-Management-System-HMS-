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
        if (!Schema::hasTable('reservation')) {
            Schema::create('reservation', function (Blueprint $table) {
                $table->id('RESERVATION_ID');
                
                $table->unsignedBigInteger('GUEST_ID');
                $table->unsignedBigInteger('ROOM_ID');
                $table->unsignedBigInteger('STAFF_ID')->nullable();
                
                $table->integer('Number_of_Guests')->nullable();
                $table->date('Check_In_Date');
                $table->date('Check_Out_Date');
                $table->decimal('Total_Amount', 10, 2)->nullable();
                $table->string('Status', 50)->default('Pending');
                
                $table->timestamps();

                // Foreign key connections
                $table->foreign('GUEST_ID')->references('GUEST_ID')->on('guest')->onDelete('cascade');
                $table->foreign('ROOM_ID')->references('ROOM_ID')->on('room')->onDelete('cascade');
                $table->foreign('STAFF_ID')->references('STAFF_ID')->on('staff')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation');
    }
};
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
        if (!Schema::hasTable('messages')) {
            Schema::create('messages', function (Blueprint $table) {
                $table->id('MESSAGE_ID'); 
                
                $table->unsignedBigInteger('GUEST_ID');
                $table->unsignedBigInteger('STAFF_ID')->nullable(); 
                
                $table->text('Message_Text');
                $table->text('Admin_Reply')->nullable();
                $table->string('Status', 50)->default('Unread');
                
                $table->timestamps(); 

                // Connect the foreign keys
                $table->foreign('GUEST_ID')->references('GUEST_ID')->on('guest')->onDelete('cascade');
                $table->foreign('STAFF_ID')->references('STAFF_ID')->on('staff')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};

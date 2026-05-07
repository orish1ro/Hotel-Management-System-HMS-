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
        if (!Schema::hasTable('room')) {
            Schema::create('room', function (Blueprint $table) {
                $table->id('ROOM_ID');
                $table->string('Room_Number', 10);
                $table->string('Room_Type', 50);
                $table->integer('Capacity');
                $table->decimal('Price_Per_Night', 10, 2);
                $table->text('Details')->nullable();
                $table->text('Inclusions')->nullable();
                $table->string('Picture_Url', 255)->nullable();
                $table->string('Status', 50);
                $table->integer('Floor_Number')->nullable();
                $table->timestamp('created_at')->nullable();
                $table->timestamp('updated_at')->nullable();
                $table->string('cleaning_status', 50)->default('Clean');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room');
    }
};

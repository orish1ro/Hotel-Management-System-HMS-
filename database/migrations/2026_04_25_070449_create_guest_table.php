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
        if (!Schema::hasTable('guest')) {
            Schema::create('guest', function (Blueprint $table) {
                $table->id('GUEST_ID');
                $table->string('First_Name', 100);
                $table->string('Last_Name', 100);
                $table->string('Email', 150)->unique();
                $table->string('Phone_Number', 20)->nullable();
                $table->string('Password', 250);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest');
    }
};

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
        if (!Schema::hasTable('staff')) {
            Schema::create('staff', function (Blueprint $table) {
                $table->id('STAFF_ID');
                $table->string('First_Name', 100);
                $table->string('Last_Name', 100);
                $table->string('Email', 150)->unique();
                $table->string('Phone', 20)->nullable();
                $table->string('Address', 255)->nullable();
                $table->string('Password', 255);
                $table->string('Role', 50)->nullable();
                $table->string('Status', 50)->default('Active');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};

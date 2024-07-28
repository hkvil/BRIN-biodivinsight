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
        Schema::create('herbariums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('observation_id')->constrained('observations');
            $table->string('seed_sample')->nullable();
            $table->string('leaf_sample')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('herbariums');
    }
};

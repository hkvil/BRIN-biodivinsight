<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        public function up()
        {
            Schema::create('soils', function (Blueprint $table) {
                $table->id();
                $table->foreignId('observation_id')->constrained('observations');
                $table->double('pH');
                $table->double('moisture');
                $table->string('temperature');
                $table->timestamps();
            });
        }
    
        public function down()
        {
            Schema::dropIfExists('soils');
        }
};

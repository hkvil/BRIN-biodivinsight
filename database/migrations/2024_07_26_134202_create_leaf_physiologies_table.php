<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        public function up()
        {
            Schema::create('leaf_physiologies', function (Blueprint $table) {
                $table->id();
                $table->foreignId('observation_id')->constrained('observations');
                $table->double('chlorophyll');
                $table->double('nitrogen');
                $table->double('leaf_moisture');
                $table->double('leaf_temperature');
                $table->timestamps();
            });
        }
    
        public function down()
        {
            Schema::dropIfExists('leaf_physiologies');
        }
    };
    

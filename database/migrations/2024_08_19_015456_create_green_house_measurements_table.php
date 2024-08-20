<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gh_measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('observation_id')->constrained('observations');
            $table->integer('no');
            $table->string('kode');
            $table->string('perlakuan_penyiraman');
            $table->decimal('tinggi_tanaman', 8, 2);
            $table->decimal('panjang_akar', 8, 2);
            $table->decimal('bb_tunas', 8, 2);
            $table->decimal('bk_tunas', 8, 2);
            $table->decimal('bb_akar', 8, 2);
            $table->decimal('bk_akar', 8, 2);
            $table->integer('minggu_panen');

            $table->decimal('mean_tinggi_tanaman', 8, 2)->nullable();
            $table->decimal('stddev_tinggi_tanaman', 8, 2)->nullable();
            $table->decimal('mean_panjang_akar', 8, 2)->nullable();
            $table->decimal('stddev_panjang_akar', 8, 2)->nullable();
            $table->decimal('mean_bb_tunas', 8, 2)->nullable();
            $table->decimal('stddev_bb_tunas', 8, 2)->nullable();
            $table->decimal('mean_bk_tunas', 8, 2)->nullable();
            $table->decimal('stddev_bk_tunas', 8, 2)->nullable();
            $table->decimal('mean_bb_akar', 8, 2)->nullable();
            $table->decimal('stddev_bb_akar', 8, 2)->nullable();
            $table->decimal('mean_bk_akar', 8, 2)->nullable();
            $table->decimal('stddev_bk_akar', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gh_measurements');
    }
};

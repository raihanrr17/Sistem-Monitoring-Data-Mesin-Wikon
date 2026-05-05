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
        Schema::create('machines', function (Blueprint $table) {
            $table->id();
            $table->string('bulan', 20);
            $table->integer('tahun');
            $table->string('plant', 10);
            $table->string('kode_mesin', 20);
            $table->float('loading_time', 10, 4);
            $table->float('operating_time', 10, 4);
            $table->float('breakdown_time', 10, 4);
            $table->integer('freq_breakdown');
            $table->text('masalah');
            $table->text('langkah_perbaikan');
            $table->text('langkah_pencegahan');
            $table->float('availability', 10, 4);
            $table->float('mtbf', 10, 4);
            $table->float('mttr', 10, 4);
            $table->string('status', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machines');
    }
};

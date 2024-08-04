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
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->uuid('student_id');
            $table->foreign('student_id')->references('id')->on('students');
            $table->date('tgl_bayar');
            $table->string('bulan_dibayar');
            $table->integer('tahun_dibayar');
            $table->uuid('spp_id');
            $table->foreign('spp_id')->references('id')->on('spps');
            $table->integer('jumlah_bayar');
            $table->string('midtrans_transaction_id')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

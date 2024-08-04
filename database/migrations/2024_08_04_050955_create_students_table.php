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
        Schema::create('students', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('nisn')->unique();
            $table->integer('nis')->unique();
            $table->string('nama');
            $table->uuid('school_class_id');
            $table->foreign('school_class_id')->references('id')->on('school_classes');
            $table->string('alamat');
            $table->string('no_telp');
            $table->uuid('spp_id');
            $table->foreign('spp_id')->references('id')->on('spps');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};

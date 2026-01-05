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
        Schema::create('bilietai', function (Blueprint $table) {
            $table->id();
            $table->string('bilieto_id', 5)->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('pavadinimas');
            $table->string('prioritetas');
            $table->string('kategorija');
            $table->string('aprasymas', 500);
            $table->timestamp('uzregistruota')->nullable();
            $table->timestamp('uzdaryta')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bilietai');
    }
};

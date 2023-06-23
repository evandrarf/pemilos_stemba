<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate_pairs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chairman_id')->unique()->constrained('candidates')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('vice_chairman_id')->unique()->constrained('candidates')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('image')->nullable();
            $table->string('vision')->nullable();
            $table->text('mission')->nullable();
            $table->integer('number')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('candidates_pair');
    }
};

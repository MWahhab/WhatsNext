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
        Schema::create('objectives', function (Blueprint $table) {
            $table->id();
            $table->string("task");
            $table->string("description");
            $table->boolean("repeat");
            $table->integer("repeat_interval")->nullable();
            $table->string("monday")->nullable();
            $table->string("tuesday")->nullable();
            $table->string("wednesday")->nullable();
            $table->string("thursday")->nullable();
            $table->string("friday")->nullable();
            $table->string("saturday")->nullable();
            $table->string("sunday")->nullable();
            $table->string("date_due");
            $table->string("time_due");
            $table->foreignId("user_fid")->references("id")->on("users");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('objectives');
    }
};

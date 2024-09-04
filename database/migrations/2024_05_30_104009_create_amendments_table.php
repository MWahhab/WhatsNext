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
        Schema::create('amendments', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_fid")->references("id")->on("users");
            $table->foreignId("objective_fid")->references("id")->on("objectives");
            $table->string("task_due_date");
            $table->boolean("accomplished");
            $table->boolean("deleted")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accomplishments');
    }
};

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
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->string('requested_for')->nullable();
            $table->string('category')->nullable();
            $table->string('priority')->nullable();
            $table->string('service_desk')->nullable();
            $table->string('assignment_group')->nullable();
            $table->string('short_description')->nullable();
            $table->text('description')->nullable();
            $table->string('predict_category')->nullable();
            $table->boolean('incident')->default(true);
            $table->dateTime('created_at_servicenow')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};

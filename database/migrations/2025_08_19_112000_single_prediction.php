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
        Schema::create('predictions', function (Blueprint $table) {
        $table->id();
        $table->text('description');
        $table->text('short_description')->nullable();
        $table->string('predict_category');
        $table->decimal('confidence_score', 5, 4)->nullable(); // e.g., 0.9876
        $table->boolean('incident')->default(true);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

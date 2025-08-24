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
        Schema::create('ml_prediction_histories', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('incident_id')->nullable(); // relation with incidents table
        $table->text('input_text'); // what text was used as input
        $table->string('predicted_label');
        $table->float('confidence')->nullable(); // e.g., 0.89
        $table->string('model_used')->nullable(); // e.g., "bert-base-v1"
        $table->string('algorithm')->nullable(); // e.g., "ML", "DL"
        $table->timestamp('predicted_at'); // when the prediction was made
        $table->string('triggered_by')->nullable(); // user or system
        $table->boolean('is_correct')->nullable(); // feedback added later
        $table->string('actual_label')->nullable(); // corrected category, if known
        $table->timestamps();

        // Optional: Add foreign key if incidents table exists
        $table->foreign('incident_id')->references('id')->on('incidents')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prediction_histories');
    }
};

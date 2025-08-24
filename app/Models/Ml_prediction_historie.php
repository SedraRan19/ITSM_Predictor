<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ml_prediction_historie extends Model
{
    use HasFactory;

    protected $table = 'ml_prediction_histories';

   
    protected $fillable = [
        'incident_id',
        'input_text',
        'predicted_label',
        'confidence',
        'model_used',
        'algorithm',
        'predicted_at',
        'triggered_by',
        'is_correct',
        'actual_label',
    ];

    protected $casts = [
        'confidence' => 'float',
        'predicted_at' => 'datetime',
        'is_correct' => 'boolean',
    ];

    /**
     * Relationship with the Incident model
     */
    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }
}

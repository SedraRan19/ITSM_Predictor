<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $table = 'incidents';

    protected $fillable = [
        'number',
        'requested_for',
        'category',
        'priority',
        'service_desk',
        'assignment_group',
        'short_description', 
        'description',
        'predict_category',
        'incident',
        'created_at_servicenow'
    ];
}

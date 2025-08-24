<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service_desk extends Model
{
    use HasFactory;

    protected $table = 'service_desks';

    protected $fillable = [
        'name',
        'sys_id'
    ];
}

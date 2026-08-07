<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'date',
        'status',
        'check_in_time',
        'check_out_time',
        'remarks',
    ];

    protected $casts = [
        'date' => 'date',
    ];
    
    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}

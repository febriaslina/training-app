<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Training extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'description',
        'duration_days',
        'requirements',
        'start_date',
        'end_date',
        'location',
        'max_quota',
        'filled_quota',
        'status',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];
    
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}

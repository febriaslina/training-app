<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registration extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'registration_code',
        'training_id',
        'customer_id',
        'verified_by',
        'status',
        'graduation_status',
        'notes',
        'operator_notes',
        'confirmed_at',
        'confirmed_via',
        'metadata',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'metadata' => 'array',
    ];
    
    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function verifier()
    {
        return $this->belongsTo(Employee::class, 'verified_by');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'registration_id',
        'facility_booking_id',
        'invoice_number',
        'total_amount',
        'status',
        'due_date',
        'paid_at',
        'line_items',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'line_items' => 'array',
        'metadata' => 'array',
    ];
    
    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function facilityBooking()
    {
        return $this->belongsTo(FacilityBooking::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FacilityBooking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'facility_id',
        'customer_id',
        'event_name',
        'start_date',
        'end_date',
        'guest_count',
        'total_cost',
        'status',
        'arrival_confirmed',
        'cancellation_fee',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'arrival_confirmed' => 'boolean',
        'metadata' => 'array',
    ];
    
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

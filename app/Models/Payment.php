<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'amount',
        'payment_method',
        'proof_file_path',
        'status',
        'verified_by',
        'paid_at',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'metadata' => 'array',
    ];
    
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function verifier()
    {
        return $this->belongsTo(Employee::class, 'verified_by');
    }
}

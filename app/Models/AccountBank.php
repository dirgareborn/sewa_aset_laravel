<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountBank extends Model
{
    protected $fillable = [
        'service_id',
        'type', // qris | va
        'bank_name',
        'account_name',
        'account_number',
        'bank_icon',
        'qris_image',
        'merchant_name',
        'merchant_id',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}

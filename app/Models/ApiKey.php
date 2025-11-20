<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'service', 'key_name', 'key_value', 'description',
    ];

    protected $casts = [
        'key_value' => 'encrypted',
    ];

    public function getMaskedValueAttribute()
    {
        $plain = $this->key_value; // sudah otomatis decrypt

        return substr($plain, 0, 4).str_repeat('●', max(strlen($plain) - 7, 0)).substr($plain, -3);
    }
}

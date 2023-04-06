<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Log extends Model
{
    protected $fillable = [
        'user_id',
        'device_id',
        'action',
        'mac',
        'name',
        'description',
    ];

    // public function device(): BelongsTo
    // {
    //     return $this->belongsTo(Device::class);
    // }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

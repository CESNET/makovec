<?php

namespace App\Models;

use App\Events\DeviceCreated;
use App\Events\DeviceDeleted;
use App\Events\DeviceUpdated;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'mac',
        'name',
        'description',
        'enabled',
        'valid_from',
        'valid_to',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'valid_from' => 'date',
        'valid_to' => 'date',
    ];

    protected $dispatchesEvents = [
        'created' => DeviceCreated::class,
        'updated' => DeviceUpdated::class,
        'deleted' => DeviceDeleted::class,
    ];

    protected function mac(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => strtoupper(preg_replace('/(..)(..)(..)(..)(..)(..)/', '\1:\2:\3:\4:\5:\6', $value)),
            set: fn ($value) => strtolower(preg_replace('/(\W)/', '', $value)),
        );
    }

    protected function validFrom(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? date('Y-m-d', strtotime($value)) : null,
        );
    }

    protected function validTo(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? date('Y-m-d', strtotime($value)) : null,
        );
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function log(): HasMany
    {
        return $this->hasMany(Log::class);
    }

    public function logCreated(): HasOne
    {
        return $this->hasOne(Log::class)->whereAction('create')->oldestOfMany();
    }

    public function logUpdated(): HasOne
    {
        return $this->hasOne(Log::class)->whereAction('update')->latestOfMany();
    }

    public function scopeSearch(Builder $query, ?string $search = null): void
    {
        $query
            ->where('mac', 'like', "%$search%")
            ->orWhere('name', 'like', "%$search%")
            ->orWhere('description', 'like', "%$search%");
    }
}

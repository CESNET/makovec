<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'description',
        'vlan',
    ];

    protected function vlan(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => preg_replace('/^vlan_(.+)$/', '\1', $value),
            set: fn ($value) => 'vlan_'.preg_replace('/\ /', '_', $value),
        );
    }

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function scopeSearch(Builder $query, ?string $search = null): void
    {
        $query
            ->where('type', 'like', "%$search%")
            ->orWhere('description', 'like', "%$search%")
            ->orWhere('vlan', 'like', "%$search%");
    }
}

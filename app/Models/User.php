<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'uniqueid',
        'email',
        'emails',
        'login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'admin' => 'boolean',
        'manager' => 'boolean',
        'active' => 'boolean',
        'login_at' => 'datetime',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    public function scopeActiveAdmins(Builder $query): void
    {
        $query
            ->where('admin', true)
            ->where('active', true);
    }

    public function scopeSearch(Builder $query, ?string $search = null): void
    {
        $query
            ->where('name', 'like', "%$search%")
            ->orWhere('uniqueid', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%");
    }

    public static function activeAdminsEmails(): array
    {
        return User::activeAdmins()
            ->select('name', 'email')
            ->get()
            ->map(fn ($u) => new Address($u->email, $u->name))
            ->toArray();
    }
}

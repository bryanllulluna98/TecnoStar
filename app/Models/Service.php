<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'suggested_price',
        'default_profiles',
        'is_active',
    ];

    protected $casts = [
        'suggested_price' => 'decimal:2',
        'default_profiles' => 'integer',
        'is_active' => 'boolean',
    ];

    public function providerAccounts(): HasMany
    {
        return $this->hasMany(ProviderAccount::class);
    }

    public function profiles(): HasManyThrough
    {
        return $this->hasManyThrough(Profile::class, ProviderAccount::class);
    }
}

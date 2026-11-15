<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProviderAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'account_identifier',
        'email',
        'password',
        'cost',
        'billing_start_date',
        'billing_due_date',
        'max_profiles',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'encrypted',
            'cost' => 'decimal:2',
            'billing_start_date' => 'date',
            'billing_due_date' => 'date',
            'max_profiles' => 'integer',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function profiles(): HasMany
    {
        return $this->hasMany(Profile::class);
    }
}

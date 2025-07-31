<?php

namespace App\Domains\Customer\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'name',
        'phone',
        'email',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    protected static function newFactory()
    {
        return \App\Domains\Customer\Database\Factories\CustomerContactFactory::new();
    }
} 
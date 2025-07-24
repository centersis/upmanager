<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status', 'hash'];

    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'projects_customers');
    }

    public function updates(): HasMany
    {
        return $this->hasMany(Update::class);
    }
} 
<?php

namespace App\Domains\Customer\Entities;

use App\Domains\Project\Entities\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'hash', 'status'];

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'projects_customers');
    }

    protected static function newFactory()
    {
        return \App\Domains\Customer\Database\Factories\CustomerFactory::new();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->hash)) {
                $model->hash = Str::uuid()->toString();
            }
        });
    }
} 
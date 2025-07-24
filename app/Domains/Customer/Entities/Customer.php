<?php

namespace App\Domains\Customer\Entities;

use App\Domains\Project\Entities\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status'];

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'projects_customers');
    }

    protected static function newFactory()
    {
        return \App\Domains\Customer\Database\Factories\CustomerFactory::new();
    }
} 
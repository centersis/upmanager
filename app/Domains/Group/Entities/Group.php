<?php

namespace App\Domains\Group\Entities;

use App\Domains\Project\Entities\Project;
use App\Domains\Group\Database\Factories\GroupFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'color', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    protected static function newFactory()
    {
        return GroupFactory::new();
    }
}

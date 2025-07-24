<?php

namespace App\Domains\Project\Entities;

use App\Domains\Customer\Entities\Customer;
use App\Domains\Group\Entities\Group;
use App\Domains\Update\Entities\Update;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'group_id', 'status', 'hash'];

    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'projects_customers');
    }

    public function updates(): HasMany
    {
        return $this->hasMany(Update::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    protected static function newFactory()
    {
        return \App\Domains\Project\Database\Factories\ProjectFactory::new();
    }
} 
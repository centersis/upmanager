<?php

namespace App\Domains\Update\Entities;

use App\Domains\Project\Entities\Project;
use App\Domains\Customer\Entities\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Update extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'customer_id', 'title', 'caption', 'description', 'views', 'hash', 'status', 'is_global'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    protected static function newFactory()
    {
        return \App\Domains\Update\Database\Factories\UpdateFactory::new();
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
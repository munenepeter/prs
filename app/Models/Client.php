<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(related: Project::class, foreignKey: 'client_id', localKey: 'id');
    }
}

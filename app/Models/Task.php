<?php

namespace App\Models;

use App\Enums\TaskUnitTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'unit_type',
        'target'
    ];

    protected $casts = [
        'unit_type' => TaskUnitTypes::class,
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(related: Project::class, foreignKey: 'project_id', ownerKey: 'id');
    }
}

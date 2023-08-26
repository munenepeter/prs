<?php

namespace App\Models;

use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'units_completed',
        'started_at',
        'ended_at',
        'reported_at'
    ];

    protected $casts = [
        'started_at'=> 'datetime:H:i:s',
        'ended_at' => 'datetime:H:i:s',
        'reported_at' => 'date:Y-m-d'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(related: User::class, foreignKey: 'user_id', ownerKey: 'id');
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(related: Task::class, foreignKey: 'task_id', ownerKey: 'id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(related: Project::class, foreignKey: 'project_id', ownerKey: 'id');
    }

    protected function duration(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->ended_at->diffAsCarbonInterval($this->started_at)
        );
    }

    protected function hourlyRate(): Attribute
    {
        return Attribute::make(
            get: function () {
                return  CarbonInterval::minutes(
                    $this->units_completed / $this->duration->totalMinutes
                )->cascade()->multiply(60)->totalMinutes;
            }
        );
    }
}

<?php

namespace App\Models;

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
        'reported_at',
        'perfomance'
    ];

    public int $aboveTarget = 0;
    public int $belowTarget = 0;

    protected $casts = [
        'started_at' => 'datetime:H:i:s',
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

                if ($this->task->unit_type->name === 'HOUR') {
                    return 0;
                }
                return  round(($this->units_completed / $this->duration->totalMinutes) * 60, 2);
            }
        );
    }

    public function calculatePerformance(): string
    {

        $performance = 'On Target';

        if ($this->reported_at > new \DateTime()) {
            $performance = 'Pending';

        } elseif ($this->task->target != 0 && $this->reported_at < new \DateTime()) {
            $percentageDifference = 0;

            if ($this->task->unit_type->name === 'HOUR') {
                $percentageDifference = number_format((($this->duration->totalMinutes - $this->task->target) / $this->task->target) * 100, 1);
            } else {
                $percentageDifference = number_format((((int)$this->hourlyRate - $this->task->target) / $this->task->target) * 100, 1);
            }

            if ($percentageDifference > 0) {
                $performance = 'Above Target by: ' . $percentageDifference.'%';

                $this->aboveTarget++;

            } elseif ($percentageDifference < 0) {
                $performance = 'Below Target by: ' . abs((float) $percentageDifference).'%';
                $this->belowTarget++;
            }
        } else {
            $performance = 'Target is Zero!';

        }

        return $performance;
    }

    protected function perfomanceColor(): Attribute
    {
        return Attribute::make(
            get: function () {
                return match (true) {
                    str_contains($this->perfomance, 'On Target') => 'blue',
                    str_contains($this->perfomance, 'Above Target') => 'green',
                    str_contains($this->perfomance, 'Below Target') => 'red',
                    str_contains($this->perfomance, 'Pending') => 'orange',
                    default => 'grey',
                };
            }
        );
    }

    protected function formattedTarget(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->task->unit_type->name === 'HOUR') {
                    return "mins/day";
                }
                return ['CHARACTERS' => 'chars', 'PAGES' => 'pgs'][$this->task->unit_type->name] . "/hr";
            }
        );
    }

}

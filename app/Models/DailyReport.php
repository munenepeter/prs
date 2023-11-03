<?php

namespace App\Models;

use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailyReport extends Model {
    use HasFactory;

    protected $fillable = [
        'units_completed',
        'started_at',
        'ended_at',
        'reported_at'
    ];

    public int $aboveTarget = 0;
    public int $belowTarget = 0;

    protected $casts = [
        'started_at' => 'datetime:H:i:s',
        'ended_at' => 'datetime:H:i:s',
        'reported_at' => 'date:Y-m-d'
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(related: User::class, foreignKey: 'user_id', ownerKey: 'id');
    }

    public function task(): BelongsTo {
        return $this->belongsTo(related: Task::class, foreignKey: 'task_id', ownerKey: 'id');
    }

    public function project(): BelongsTo {
        return $this->belongsTo(related: Project::class, foreignKey: 'project_id', ownerKey: 'id');
    }

    protected function duration(): Attribute {
        return Attribute::make(
            get: fn () => $this->ended_at->diffAsCarbonInterval($this->started_at)
        );
    }

    protected function hourlyRate(): Attribute {
        return Attribute::make(
            get: function () {

                if ($this->task->unit_type->name === 'HOUR') {
                    return 0;
                }
                return  round(($this->units_completed / $this->duration->totalMinutes) * 60, 2);
            }
        );
    }
    protected function calculatePerformance(): array {
        $performance = [
            'status' => 'On Target',
            'color' => 'blue'
        ];

        if ($this->reported_at > new \DateTime()) {
            $performance = [
                'status' => 'Pending',
                'color' => 'orange'
            ];
        } elseif ($this->task->target != 0 && $this->reported_at < new \DateTime()) {
            $percentageDifference = 0;

            if ($this->task->unit_type->name === 'HOUR') {
                $percentageDifference = number_format((($this->duration->totalMinutes - $this->task->target) / $this->task->target) * 100, 1);
            } else {
                $percentageDifference = number_format((((int)$this->hourlyRate - $this->task->target) / $this->task->target) * 100, 1);
            }

            if ($percentageDifference > 0) {
                $performance = [
                    'status' => 'Above Target by: ' . $percentageDifference.'%',
                    'color' => 'green'
                ];
                $this->aboveTarget++;

            } elseif ($percentageDifference < 0) {
                $performance = [
                    'status' => 'Below Target by: ' . abs((float) $percentageDifference).'%',
                    'color' => 'red'
                ];
                $this->belowTarget++;
            }
        } else {
            $performance = [
                'status' => 'Target is Zero!',
                'color' => 'gray'
            ];
        }

        return $performance;
    }
    protected function perfomance(): Attribute {
        return Attribute::make(
            get: function () {
                return $this->calculatePerformance();
            }
        );
    }


    protected function formattedTarget(): Attribute {
        return Attribute::make(
            get: function () {
                if ($this->task->unit_type->name === 'HOUR') {
                    return "mins/day";
                }
                return ['CHARACTERS' => 'chars', 'PAGES' => 'pgs'][$this->task->unit_type->name] . "/hr";
            }
        );
	}
	public function getTested(string $vaccine) {
		return strtoupper($vaccine);
	}
}

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
                } else {
                    if ($this->reported_at > new \DateTime()) {
                        return number_format(($this->units_completed / $this->duration->totalMinutes) * 60, 2);
                    }
                }
                return  number_format(($this->units_completed / $this->duration->totalMinutes) * 60, 2);
            }
        );
    }
    protected function perfomance(): Attribute {
        return Attribute::make(
            get: function () {
                // Get the individual target for this associate
                $individualTarget = $this->task->target;

                // Initialize variables
                $percentageDifference = 0;
                $performance = [];


                // Check if $individualTarget is not zero
                if ($individualTarget != 0 && $this->reported_at < new \DateTime()) {
                    // Calculate the percentage difference from the target
                    if ($this->task->unit_type->name === 'HOUR') {
                        $percentageDifference = number_format((($this->duration->totalMinutes - $individualTarget) / $individualTarget) * 100, 1);
                    } else {
                        $percentageDifference = number_format((((int)$this->hourlyRate - $individualTarget) / $individualTarget) * 100, 1);
                    }

                    // Determine if performance is above or below the target
                    if ($percentageDifference > 0) {
                        $performance['status'] = 'Perfomance: ' . $percentageDifference;
                        $performance['color'] = 'green';
                        $this->aboveTarget++;
                    } elseif ($percentageDifference < 0) {
                        $performance['status'] = 'Perfomance: ' . abs((float) $percentageDifference);
                        $performance['color'] = 'red';
                        $this->belowTarget++;
                    } else {
                        $performance['status'] = 'On Target';
                        $performance['color'] = 'blue';
                        $this->belowTarget = 0;
                        $this->aboveTarget = 0;
                    }
                } elseif ($this->reported_at > new \DateTime()) {
                    $performance['status'] = 'Pending';
                    $performance['color'] = 'orange';
                    $this->belowTarget = 0;
                    $this->aboveTarget = 0;
                } else {

                    $performance['status'] = 'Target is Zero';
                    $performance['color'] = 'gray';
                }
                return $performance;
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
}

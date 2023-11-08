<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(
                model: App\Models\User::class,
                column: 'user_id'
            )
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(
                model: App\Models\Project::class,
                column: 'project_id'
            )
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(
                model: App\Models\Task::class,
                column: 'task_id'
            )
                ->constrained()
                ->cascadeOnDelete();
            $table->unsignedMediumInteger('units_completed');
            $table->time('started_at');
            $table->time('ended_at');
            $table->string('perfomance');
            $table->date('reported_at');
            $table->timestamps();

            $table->index(['user_id', 'project_id', 'task_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_reports');
    }
};

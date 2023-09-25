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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(
                model: \App\Models\Project::class
            )->index()
                ->constrained()
                ->cascadeOnDelete();
            $table->string('name');
            $table->unsignedTinyInteger('unit_type')
                ->comment('Represents task unit type. It will be backed up by UnitType enum')
                ->default(\App\Enums\TaskUnitTypes::HOUR->value);
            $table->unsignedSmallInteger('target');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};

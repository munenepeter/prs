<?php

use App\Models\User;
use App\Models\Client;
use App\Enums\ProjectStatuses;
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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->unsignedTinyInteger('status')
                ->comment('This will be backed up by ProjectStatus enum')
                ->default(ProjectStatuses::LIVE->value);
            $table->foreignIdFor(
                model: User::class,
                column: 'user_id'
            )->comment('Project manager of the project')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(
                model: Client::class,
                column: 'client_id'
            )
                ->constrained()
                ->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'client_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
};

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
        Schema::create('role_user', function (Blueprint $table) {
            $table->foreignIdFor(
                model: \App\Models\Role::class,
                column: 'role_id'
            )->constrained()->cascadeOnDelete();
            $table->foreignIdFor(
                model: \App\Models\User::class,
                column: 'user_id'
            )->constrained()->cascadeOnDelete();

            $table->index(['role_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_user');
    }
};

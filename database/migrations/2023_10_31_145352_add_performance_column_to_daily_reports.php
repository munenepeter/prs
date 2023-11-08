<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class() extends Migration {
    public function up()
    {
        Schema::table('daily_reports', function (Blueprint $table) {
            $table->decimal('performance', 5, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('daily_reports', function (Blueprint $table) {
            $table->dropColumn('performance');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_texts', function (Blueprint $table) {
            $table->string('description')->primary();
            $table->string('text');
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->foreign('description')->references('description')->on('activity_texts')->cascadeOnUpdate()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_texts');
        Schema::table('activities', function (Blueprint $table) {
           $table->dropIndex('description');
        });
    }
};

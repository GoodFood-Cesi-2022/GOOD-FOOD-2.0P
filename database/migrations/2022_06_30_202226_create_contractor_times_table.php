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
        Schema::create('contractor_times', function (Blueprint $table) {
            $table->id();
            $table->string('monday_lunch_opened_at')->nullable();
            $table->string('monday_lunch_closed_at')->nullable();
            $table->string('monday_night_opened_at')->nullable();
            $table->string('monday_night_closed_at')->nullable();
            $table->string('tuesday_lunch_opened_at')->nullable();
            $table->string('tuesday_lunch_closed_at')->nullable();
            $table->string('tuesday_night_opened_at')->nullable();
            $table->string('tuesday_night_closed_at')->nullable();
            $table->string('wednesday_lunch_opened_at')->nullable();
            $table->string('wednesday_lunch_closed_at')->nullable();
            $table->string('wednesday_night_opened_at')->nullable();
            $table->string('wednesday_night_closed_at')->nullable();
            $table->string('thursday_lunch_opened_at')->nullable();
            $table->string('thursday_lunch_closed_at')->nullable();
            $table->string('thursday_night_opened_at')->nullable();
            $table->string('thursday_night_closed_at')->nullable();
            $table->string('friday_lunch_opened_at')->nullable();
            $table->string('friday_lunch_closed_at')->nullable();
            $table->string('friday_night_opened_at')->nullable();
            $table->string('friday_night_closed_at')->nullable();
            $table->string('saturday_lunch_opened_at')->nullable();
            $table->string('saturday_lunch_closed_at')->nullable();
            $table->string('saturday_night_opened_at')->nullable();
            $table->string('saturday_night_closed_at')->nullable();
            $table->string('sunday_lunch_opened_at')->nullable();
            $table->string('sunday_lunch_closed_at')->nullable();
            $table->string('sunday_night_opened_at')->nullable();
            $table->string('sunday_night_closed_at')->nullable();
            $table->foreignId('contractor_id')->constrained('contractors')->onDelete('cascade');
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

        Schema::table('contractor_times', function (Blueprint $table) {
            $table->dropForeign(['contractor_id']);
        });

        Schema::dropIfExists('contractor_times');
    }
};

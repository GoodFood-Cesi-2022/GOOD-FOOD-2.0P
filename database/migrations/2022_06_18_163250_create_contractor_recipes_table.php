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
        Schema::create('contractor_recipes', function (Blueprint $table) {
            $table->id();
            $table->decimal('price');
            $table->foreignId('recipe_id')->constrained('recipes');
            $table->foreignId('contractor_id')->constrained('contractors');
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

        Schema::table('contractor_recipes', function (Blueprint $table) {
            $table->dropForeign(['recipe_id']);
            $table->dropForeign(['contractor_id']);
        });


        Schema::dropIfExists('contractor_recipes');
    }
};

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
        Schema::create('ingredient_ingredient_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ingredient_id')->constrained('ingredients');
            $table->foreignId('ingredient_type_id')->constrained('ingredient_types');
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
        Schema::table('ingredient_ingredient_types', function (Blueprint $table) {
            $table->dropForeign(['ingredient_id']);
            $table->dropForeign(['ingredient_type_id']);
        });

        Schema::dropIfExists('ingredient_ingredient_types');
    }
};

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
        Schema::create('order_recipes', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->text('comment')->nullable();
            $table->decimal('price_unit');
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('recipe_id')->constrained('recipes')->onDelete('cascade');
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
        Schema::table('order_recipes', function(Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropForeign(['recipe_id']);
        });

        Schema::dropIfExists('order_recipes');
    }
};

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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('vat_code_id')->constrained('vat_codes')->onDelete('cascade');
            $table->foreignId('contractor_id')->constrained('contractors')->onDelete('cascade');
            $table->foreignId('address_id')->constrained('addresses')->onDelete('set null')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function(Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['vat_code_id']);
            $table->dropForeign(['contractor_id']);
        });

        Schema::dropIfExists('orders');
    }
};
